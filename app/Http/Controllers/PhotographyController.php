<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

// Models
use App\Models\Photography\Shoots;

// Requests
use App\Http\Requests\Photography\StoreShootRequest;

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
    public function storeShoot(StoreShootRequest $request)
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

    public function editShoot(Shoots $shoot)
    {
        
    }
}
