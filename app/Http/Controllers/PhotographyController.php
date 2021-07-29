<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Image;
use DB;

// Models
use App\Models\Photography\Shoots;
use App\Models\Photography\Photos;

// Requests
use App\Http\Requests\Photography\ShootRequest;
use App\Http\Requests\Photography\PhotoRequest;

class PhotographyController extends Controller
{
    /**
     * Instantiate a new PhotographyController instance.
     */
    public function __construct()
    {
        $this->action_nav_opts = [
            [
                'text' => 'Add Shoot',
                'route' => 'admin.photography.shoot.add',
                'icon' => 'add',
            ],
            [
                'text' => 'Edit Shoot',
                'route' => 'admin.photography.shoot.edit',
                'icon' => 'camera',
            ],
            [
                'text' => 'Upload Photos',
                'route' => 'admin.photography.photos.upload',
                'icon' => 'upload',
            ],
            [
                'text' => 'Edit Photos',
                'route' => 'admin.photography.photos.edit',
                'icon' => 'photo',
            ],
        ];
    }

    /**
     * Return the main photography portfolio page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $photos = Photos::where('show_on_home', 1);

        $filter_categories = array();
        if($request->has('filters'))
        {
            $filter_categories = $request->get('filters');
            if(count($filter_categories) > 0)
            {
                $photo_ids =
                    DB::table('photo_categories')
                        ->whereIn('category_id', $filter_categories)
                        ->get()->pluck('photo_id')->toArray();

                $photos = $photos->whereIn('id', $photo_ids);
            }
        }
        
        $photos = $photos->get();

        return view('photography')->with([
            'stylesheet' => 'photography',
            'filter_categories' => $filter_categories,
            'photos' => $photos,
        ]);
    }

    /**
     * Return the photography view with photos
     * from a specific shoot
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewShoot(Request $request, Shoots $shoot)
    {
        $photos = Photos::where('shoot_id', $shoot->id);

        $filter_categories = array();
        if($request->has('filters'))
        {
            $filter_categories = $request->get('filters');
            if(count($filter_categories) > 0)
            {
                $photo_ids =
                    DB::table('photo_categories')
                        ->whereIn('category_id', $filter_categories)
                        ->get()->pluck('photo_id')->toArray();
                $photos = $photos->whereIn('id', $photo_ids);
            }
        }
        
        $photos = $photos->get();

        return view('photography')->with([
            'stylesheet' => 'photography',
            'filter_categories' => $filter_categories,
            'photos' => $photos,
        ]);
    }

    /**
     * Return the view with the form to add a shoot
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function addShoot()
    {
        return view('admin.photography.shoots.form')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Add New Shoot',
        ]);
    }

    /**
     * Stores a new photography shoot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeShoot(ShootRequest $request)
    {
        // Create a new shoot
        $shoot = new Shoots([
            'name' => $request->get('name'),
        ]);

        if(\Auth::check())
        {
            // Set shot on date
            if($request->has('date'))
            {
                $date = $request->get('date');
                if(!is_null($date))
                {
                    $shoot->shot_on = Carbon::parse($date)->format('Y-m-d');
                }
            }

            // Set shoot description
            if($request->has('desc'))
            {
                $shoot->desc = $request->get('desc');
            }

            // Save the shoot
            if(!$shoot->save())
            {
                // Log errors
                Log::error('Failed to save new shoot', [
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to save a new shoot, see logs',
                ]);
            }

            // Associate default shoot categories
            foreach(config('photography.categories') as $id => $category)
            {
                if($request->has("category-$id"))
                {
                    if(!DB::table('shoot_categories')->insert([
                        'shoot_id' => $shoot->id,
                        'category_id' => $id,
                    ]))
                    {
                        // Log error
                        $category_name = $category['name'];
                        Log::error("Failed to save category $category_name as a default category for shoot $shoot->name", [
                            'shoot->id' => $shoot->id,
                            'category_id' => $id,
                        ]);
                    }
                }
            }
        }
        else
        {
            $shoots = Shoots::all();
            if(!is_null($shoots) && $shoots->count() > 0)
            {
                $shoot->id = $shoots->first()->id;
            }
            else
            {
                return redirect()->route('admin.photography.shoot.edit')->with([
                    'success_alert' => "Created new shoot $shoot->name",
                ]);
            }
        }

        return redirect()->route('admin.photography.shoot.edit', ['shoot' => $shoot->id])->with([
            'success_alert' => "Created new shoot $shoot->name",
        ]);
    }

    /**
     * Returns either the view to select a shoot to edit or
     * the shoot form with all the values filled in to edit
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editShoot(Shoots $shoot = null)
    {
        if(!is_null($shoot))
        {
            $shoot->setCategoriesArray();

            return view('admin.photography.shoots.form')->with([
                'action_nav_opts' => $this->action_nav_opts,
                'card_header' => 'Edit Shoot',
                'shoot' => $shoot,
            ]);
        }

        // Get all shoots
        $shoots = Shoots::all();

        // Return the shoot selector view
        return view('admin.photography.shoots.selector')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Select Shoot',
            'shoots' => $shoots,
        ]);
    }

    public function updateShoot(Shoots $shoot, ShootRequest $request)
    {
        if(\Auth::check())
        {
            // Update name
            $shoot->name = $request->get('name');

            // Set shot on date
            if($request->has('date'))
            {
                $date = $request->get('date');
                if(!is_null($date))
                {
                    $shoot->shot_on = Carbon::parse($date)->format('Y-m-d');
                }
                else
                {
                    $shoot->shot_on = null;
                }
            }
            else
            {
                $shoot->shot_on = null;
            }

            // Update shoot description
            $shoot->desc = $request->get('desc');

            // Save the shoot
            if(!$shoot->save())
            {
                // Log errors
                Log::error('Failed to update shoot', [
                    'shoot' => $shoot->toArray(),
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to update shoot, see logs',
                ]);
            }

            // Clear the categories relationship table
            DB::table('shoot_categories')->where('shoot_id', $shoot->id)->delete();

            // Associate default shoot categories
            foreach(config('photography.categories') as $id => $category)
            {
                if($request->has("category-$id"))
                {
                    if(!DB::table('shoot_categories')->insert([
                        'shoot_id' => $shoot->id,
                        'category_id' => $id,
                    ]))
                    {
                        // Log error
                        $category_name = $category['name'];
                        Log::error("Failed to save category $category_name as a default category when updating shoot $shoot->name", [
                            'shoot->id' => $shoot->id,
                            'category_id' => $id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.photography.shoot.edit', ['shoot' => $shoot->id])->with([
            'success_alert' => "Updated shoot $shoot->name",
        ]);
    }

    /**
     * Delete the selected shoot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyShoot(Shoots $shoot)
    {
        if(\Auth::check())
        {
            if(!$shoot->delete())
            {
                // Log errors
                Log::error('Failed to delete shoot', [
                    'shoot' => $shoot->toArray(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to delete shoot, see logs',
                ]);
            }
        }

        return redirect()->route('admin.photography.shoot.edit')->with([
            'success_alert' => "Successfully deleted shoot $shoot->name",
        ]);
    }

    public function uploadPhotos(Shoots $shoot = null)
    {
        $shoots = Shoots::all();

        $shoot_id = null;
        if(!is_null($shoot))
        {
            $shoot_id = $shoot->id;
        }

        return view('admin.photography.photos.upload')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Upload Photos',
            'shoots' => $shoots,
            'shoot_id' => $shoot_id,
        ]);
    }

    public function storePhoto(Shoots $shoot, Request $request)
    {
        if(\Auth::check())
        {
            // Load other photos in shoot
            $shoot->load('photos');

            // Create photo
            $caption = $shoot->name . ' - ' . ($shoot->photos->count() + 1);
            $photo = new Photos([
                'caption' => $caption,
                'shoot_id' => $shoot->id,
            ]);

            // Upload image
            $path = 'public/images/photography/fullres';
            $photo->asset = str_replace($path . '/', '', $request->file->store($path));

            // Save photo entry
            if(!$photo->save())
            {
                // Log error
                Log::error('Failed to save new photo', [
                    'photo' => $photo->toArray(),
                    'shoot' => $shoot->toArray(),
                    'request' => $request->all(),
                ]);

                // Return json error
                return response()->json([
                    'error' => 'Failed to save photo, see logs.',
                ]);
            }

            // Create compressed version
            if(!Image::make(storage_path('app/' . $path . '/') . $photo->asset)->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(storage_path('app/public/images/photography/compressed/' . $photo->asset)))
            {
                // Log error
                Log::error('Failed to save compressed photo', [
                    'photo' => $photo->toArray(),
                    'shoot' => $shoot->toArray(),
                ]);

                // Return json error
                return response()->json([
                    'error' => 'Failed to compress photo, see logs.',
                ]);
            }

            // Assign categories
            $shoot->setCategoriesArray();
            foreach(config('photography.categories') as $id => $category)
            {
                $errors = 0;
                if(in_array($id, $shoot->categories))
                {
                    if(!DB::table('photo_categories')->insert([
                        'photo_id' => $photo->id,
                        'category_id' => $id,
                    ]))
                    {
                        // Log error
                        $category_name = $category['name'];
                        Log::error("Failed to associated default shoot category $category_name to photo $photo->name", [
                            'photo->id' => $photo->id,
                            'category_id' => $id,
                        ]);

                        $errors++;
                    }
                }

                if($errors > 0)
                {
                    // Return json error
                    return response()->json([
                        'error' => "Failed to associate $errors categories to photo, see logs.",
                    ]);
                }
            }
        }

        return response()->json();
    }

    public function editPhotos(Shoots $shoot = null)
    {
        if(!is_null($shoot))
        {
            $shoot->load('photos');

            foreach($shoot->photos as $photo)
            {
                $photo->setCategoriesArray();
            }

            return view('admin.photography.photos.manage')->with([
                'action_nav_opts' => $this->action_nav_opts,
                'card_header' => 'Edit Photos',
                'shoot' => $shoot,
            ]);
        }

        // Get all shoots
        $shoots = Shoots::all();

        // Return the shoot selector view
        return view('admin.photography.shoots.selector')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Select Shoot',
            'shoots' => $shoots,
        ]);
    }

    public function updatePhoto(Photos $photo, PhotoRequest $request)
    {
        if(\Auth::check())
        {
            // Update caption
            $photo->caption = $request->get('caption');

            // Update photo show on home
            $photo->show_on_home = $request->has('show-on-home');

            // Save the photo
            if(!$photo->save())
            {
                // Log errors
                Log::error('Failed to update photo', [
                    'photo' => $photo->toArray(),
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to update photo, see logs',
                ]);
            }

            // Clear the categories relationship table
            DB::table('photo_categories')->where('photo_id', $photo->id)->delete();

            // Associate default shoot categories
            foreach(config('photography.categories') as $id => $category)
            {
                if($request->has("category-$id"))
                {
                    if(!DB::table('photo_categories')->insert([
                        'photo_id' => $photo->id,
                        'category_id' => $id,
                    ]))
                    {
                        // Log error
                        $category_name = $category['name'];
                        Log::error("Failed to save category $category_name to photo $photo->name", [
                            'photo->id' => $photo->id,
                            'category_id' => $id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.photography.photos.edit', ['shoot' => $photo->shoot_id])->with([
            'success_alert' => "Updated photo $photo->caption",
        ]);
    }

    public function destroyPhoto(Photos $photo)
    {
        if(\Auth::check())
        {
            if(!$photo->delete())
            {
                // Log errors
                Log::error('Failed to delete photo', [
                    'photo' => $photo->toArray(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to delete shoot, see logs',
                ]);
            }
        }

        return redirect()->route('admin.photography.photos.edit', [
            'shoot' => $photo->shoot_id,
        ])->with([
            'success_alert' => "Successfully deleted photo $photo->caption",
        ]);
    }
}
