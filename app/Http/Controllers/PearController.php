<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

// Models
use App\Models\Photography\Shoots;
use App\Models\Photography\Photos;
use App\Models\User;

// Notifications
use App\Notifications\PearContact;

// Requests
use App\Http\Requests\Contact\PearContactRequest;

class PearController extends Controller
{
    /**
     * Gets all images that belong on the home view
     *
     * @return \Illuminate\Http\Response
     */
    public function home($category_id = null)
    {
        // Get all photos with come view - load categories
        $photos = Photos::where('show_on_home', 1);
        if (!is_null($category_id)) {
            $photos = $photos->whereHas('photoCategory', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        $photos = $photos->inRandomOrder()->get();

        // Set response properties
        $response = array();
        $response['success'] = true;
        $response['num_photos'] = count($photos);

        // Set photos array
        $response['photos'] = $this->photosResponseArray($photos);

        return response()->json($response);
    }

    /**
     * Gets all the images for a specific shoot
     *
     * @return \Illuminate\Http\Response
     */
    public function shoot($slug)
    {
        // Try to find the shoot
        $shoot = Shoots::where('pear_slug', $slug)->with('photos')->firstOrFail();

        // Set response properties
        $response = array();
        $response['success'] = true;
        $response['num_photos'] = count($shoot->photos);

        // Set photos array
        $response['photos'] = $this->photosResponseArray($shoot->photos);

        return response()->json($response);
    }

    /**
     * Recieves a contact request from the portrait pear portfolio
     * contact form and sends me an email notification
     *
     * @return \Illuminate\Http\Response
     */
    public function contact(PearContactRequest $request)
    // public function contact()
    {
        // Email me the contact request
        $user = User::first();
        $user->notify(new PearContact(
            $name = $request->name,
            $contact_info = $request->contact_info,
            $reason = $request->reason,
            $instagram = $request?->instagram,
            $message = $request->message,
        ));

        // Set response properties
        $response = array();
        $response['success'] = true;
        $response['message'] = 'The contact request was sent.';

        return response()->json($response);
    }

    private function photosResponseArray($photos) {
        $photos_array = array();
        foreach ($photos as $photo) {
            // set image properties
            $photo->setCategoriesArray();
            $array = [
                'id' => $photo->id,
                'compressed_asset_url' => Storage::url(config('filesystems.dir.photography.compressed')) . $photo->asset,
                'full_res_asset_url' => Storage::url(config('filesystems.dir.photography.fullres')) . $photo->asset,
                'num_categories' => count($photo->categories),
                'categories_array' => $photo->categories,
            ];
            array_push($photos_array, $array);
        }
        return $photos_array;
    }
}
