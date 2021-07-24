<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// Models
use App\Models\Photography\Shoots;

// Requests
use App\Http\Requests\Photography\ShootRequest;

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
    {
        
    }
}
