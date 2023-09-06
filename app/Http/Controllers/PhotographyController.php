<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
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
            [
                'text' => 'Pear',
                'route' => 'admin.photography.shoot.pear',
                'icon' => 'pear',
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
        // Get all photos that are set to be shown on the home page of the photography portfolio
        $photos = Photos::where('show_on_home', 1);

        $filter_categories = array();
        if($request->has('filters')) // Check if any filters are set
        {
            // Set filters
            $filter_categories = $request->get('filters');
            if(count($filter_categories) > 0) // Verify filter categories aren't blank
            {
                $photo_ids = // Get the ids of all photos with that category
                    DB::table('photo_categories')
                        ->whereIn('category_id', $filter_categories)
                        ->get()->pluck('photo_id')->toArray();

                // Add the photo ids as a query param on the query builder
                $photos = $photos->whereIn('id', $photo_ids);
            }
        }
        
        // Check if there is already a seed set on the session for randomizing the photo order
        $rand_seed = session('rand_seed');
        if(is_null($rand_seed)) // If no seed is set...
        {
            // Generate 4 digit integer seed
            $rand_seed = rand(1000, 9999);
            session(['rand_seed' => $rand_seed]); // And save it to the session
        }

        // Get the photos to display using the seed to randomize the order displayed
        $photos = $photos->inRandomOrder($rand_seed)->get();

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
        // Get all photos that are part of the requested shoot
        $photos = Photos::where('shoot_id', $shoot->id);

        $filter_categories = array();
        if($request->has('filters')) // Check if any filters are set
        {
            // Set filters
            $filter_categories = $request->get('filters');
            if(count($filter_categories) > 0) // Verify filters aren't blank
            {
                $photo_ids = // Get all photo IDs that have that category
                    DB::table('photo_categories')
                        ->whereIn('category_id', $filter_categories)
                        ->get()->pluck('photo_id')->toArray();

                // Filter by those photo ids on the query builder
                $photos = $photos->whereIn('id', $photo_ids);
            }
        }
        
        // Execute the query builder
        $photos = $photos->get();

        return view('photography')->with([
            'stylesheet' => 'photography',
            'filter_categories' => $filter_categories,
            'photos' => $photos,
            'shoot' => $shoot,
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

        // Verify user is admin authenticted
        if(\Auth::check())
        {
            // Set shot on date
            if($request->has('date'))
            {
                $date = $request->get('date');
                if(!is_null($date)) // Verify param isn't blank
                {
                    // Format to Y-m-d for storing in DB
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
        else // If user is guest
        {
            // Get all the shoots
            $shoots = Shoots::all();
            if(!is_null($shoots) && $shoots->count() > 0)
            {
                $shoot->id = $shoots->first()->id; // Get a random shoot ID to redirect to
            }
            else
            {
                return redirect()->route('admin.photography.shoot.edit')->with([
                    'success_alert' => "Created new shoot $shoot->name", // And set the name of that random shoot for success alert
                ]);
            }
        }

        return redirect()->route('admin.photography.shoot.edit', ['shoot' => $shoot->id])->with([
            'success_alert' => "Created new shoot $shoot->name", // For blade pop up alert
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
        // If the shoot is set
        if(!is_null($shoot))
        {
            // Get all the default categories for the shoot
            $shoot->setCategoriesArray();

            // Return the form to edit the shoot
            return view('admin.photography.shoots.form')->with([
                'action_nav_opts' => $this->action_nav_opts,
                'card_header' => 'Edit Shoot',
                'shoot' => $shoot,
            ]);
        }

        // Get all shoots
        $shoots = Shoots::orderBy('name')->get();

        // Return the shoot selector view
        return view('admin.photography.shoots.selector')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Select Shoot',
            'shoots' => $shoots,
        ]);
    }

    /**
     * Updates the properties of a photo shoot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateShoot(Shoots $shoot, ShootRequest $request)
    {
        // Check if user is admin authenticated
        if(\Auth::check())
        {
            // Update name
            $shoot->name = $request->get('name');

            // Set shot on date
            if($request->has('date'))
            {
                $date = $request->get('date');
                if(!is_null($date)) // Verify date param isn't blank
                {
                    // Format to Y-m-d for storing in the DB
                    $shoot->shot_on = Carbon::parse($date)->format('Y-m-d');
                }
                else
                {
                    // Clear the shot on date if blank
                    $shoot->shot_on = null;
                }
            }
            else
            {
                // Clear the shot on blank if param is missing
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
            'success_alert' => "Updated shoot $shoot->name", // For blade pop up alert
        ]);
    }

    /**
     * Delete the selected shoot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyShoot(Shoots $shoot)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Attempt to soft delete shoot
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
            else
            {
                // Get all photos associated with the shoot
                $shoot->load('photos');

                // Delete photos
                foreach($shoot->photos as $photo)
                {
                    // Attempt to soft delete the photo
                    if(!$photo->delete()) // Notice: this does not delete the asset files
                    {
                        // Log errors
                        Log::error('Failed to delete photo when deleting shoot', [
                            'photo' => $photo->toArray(),
                            'shoot' => $shoot->toArray(),
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.photography.shoot.edit')->with([
            'success_alert' => "Successfully deleted shoot $shoot->name",
        ]);
    }

    /**
     * Returns the plugin to upload photos to a 
     * selected shoot
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function uploadPhotos(Shoots $shoot = null)
    {
        // Get all shoots available to upload photos to
        $shoots = Shoots::orderBy('name')->get();

        // If the shoot is already set from the 'Upload Photos' redirect button...
        $shoot_id = null;
        if(!is_null($shoot))
        {
            // Pass it to the blade to have it auto selected on the drop down
            $shoot_id = $shoot->id;
        }

        return view('admin.photography.photos.upload')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Upload Photos',
            'shoots' => $shoots,
            'shoot_id' => $shoot_id,
        ]);
    }

    /**
     * Returns a json response with any errors after
     * uploading a single photo async
     *
     * @return \Illuminate\Http\Response
     */
    public function storePhoto(Shoots $shoot, Request $request)
    {
        // Verify user is admin authenticated
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
            $photo->asset = str_replace(config('filesystems.dir.photography.fullres') . '/', '', Storage::put(config('filesystems.dir.photography.fullres'), $request->file));

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
            if(!Storage::put(config('filesystems.dir.photography.compressed') . $photo->asset, Image::make(Storage::get(config('filesystems.dir.photography.fullres') . $photo->asset))->resize(1000, 1000, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->stream()->__toString()))
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

        return response()->json(); // For ajax response
    }

    /**
     * Returns the view to manaage all photos associated
     * with the selected photoshoot
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editPhotos(Shoots $shoot = null)
    {
        // If shoot is set to edit photos for
        if(!is_null($shoot))
        {
            // Load all photos associated with the shoot
            $shoot->load('photos');

            // Load the categories for each photo in the shoot
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
        $shoots = Shoots::orderBy('name')->get();

        // Return the shoot selector view
        return view('admin.photography.shoots.selector')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Select Shoot',
            'shoots' => $shoots,
        ]);
    }

    /**
     * Updates caption, categories, and show on home
     * for a photo
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoto(Photos $photo, PhotoRequest $request)
    {
        // Verify user is admin authenticated
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

        return redirect()->route('admin.photography.photos.edit', ['shoot' => $photo->shoot_id, "#photo-card-$photo->id"])->with([
            'success_alert' => "Updated photo $photo->caption",
        ]);
    }

    /**
     * Destroy the selected photo
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPhoto(Photos $photo)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Attempt to soft delete photo
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

    /**
     * Returns either the view to select a shoot to edit or
     * the shoot with an ability to see or toggle the slug
     * and link for portrait pear
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editPear(Shoots $shoot = null)
    {
        // If the shoot is set
        if(!is_null($shoot))
        {
            // Return the pear to edit the shoot
            return view('admin.photography.shoots.pear')->with([
                'action_nav_opts' => $this->action_nav_opts,
                'card_header' => 'Pear Shoot',
                'shoot' => $shoot,
            ]);
        }

        // Get all shoots
        $shoots = Shoots::orderBy('name')->get();

        // Return the shoot selector view
        return view('admin.photography.shoots.selector')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Select Pear',
            'shoots' => $shoots,
        ]);
    }

    /**
     * Roll or remove Pear slug
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePear(Shoots $shoot)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Check if already set
            if (!is_null($shoot->pear_slug)) {
                // Remove slug
                $shoot->pear_slug = null;
            } else {
                $shoot->pear_slug = $this->generateSlug();
            }

            // Save
            if (!$shoot->save()) {
                // Log errors
                \Log::error('Failed to re-roll Pear slug', [
                    'shoot' => $shoot->toArray(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to re-roll Pear slug, see logs',
                ]);
            }
        }

        return redirect()->route('admin.photography.shoot.pear', ['shoot' => $shoot->id])->with([
            'success_alert' => "Successfully re-rolled Pear slug for $shoot->name",
        ]);
    }

    private function generateSlug($length = 6) {
        $original_string = array_merge(range(0,29), range('A', 'Z'));
        $original_string = implode("", $original_string);
        return substr(str_shuffle($original_string), 0, $length);
    }
}
