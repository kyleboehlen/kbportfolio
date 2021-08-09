<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use DB;

// Models
use App\Models\Software\Projects;

// Requests
use App\Http\Requests\Software\StoreRequest;
use App\Http\Requests\Software\UpdateRequest;

class SoftwareController extends Controller
{
    /**
     * Instantiate a new ResumeController instance.
     */
    public function __construct()
    {
        $this->action_nav_opts = [
            [
                'text' => 'Add',
                'route' => 'admin.software.add',
                'icon' => 'add',
            ],
        ];

        foreach(Projects::all() as $project)
        {
            array_push($this->action_nav_opts, [
                'text' => $project->name,
                'route' => 'admin.software.edit',
                'params' => [
                    'project' => $project->id,
                ],
                'icon' => 'code',
            ]);
        }
    }
    
     /**
     * Returns the portfolio view of software projects
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function projects()
    {
        // Get all the software projects
        $projects = Projects::all();
        foreach($projects as $project)
        {
            // Load all of the technologies used in each software project
            $project->setTechnologiesArray();
        }

        // Get all available technologies for displaying badges
        $technologies = config('software.technologies');

        return view('software')->with([
            'stylesheet' => 'software',
            'projects' => $projects,
            'technologies' => $technologies,
        ]);
    }

    /**
     * The admin form to create a new software project
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add()
    {
        return view('admin.software.form')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Add Project',
        ]);
    }

    /**
     * Stores a new software project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequest $request)
    {
        // Create new project
        $project = new Projects([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'desc' => $request->get('desc'),
            'app_link' => $request->get('app-link'),
        ]);

        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Check if it has a private codebase
            if(!$request->has('private-codebase'))
            {
                $project->codebase_link = $request->get('codebase_link');
            }

            // Save the project
            if(!$project->save())
            {
                // Log errors
                Log::error('Failed to save new project', [
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to save a new software project, see logs',
                ]);
            }

            // Upload logo
            $project->logo = $request->file('logo')->store('public/images/software');
            $project->logo = str_replace('public/images/software/', '', $project->logo);

            if(!$project->save())
            {
                // Log errors
                Log::error('Failed to associate logo with software project', [
                    'project->id' => $project->id,
                    'project->logo' => $project->logo,
                ]);
            }

            // Add relationships for all of the technologies
            foreach(config('software.technologies') as $id => $technology)
            {
                if($request->has("technology-$id"))
                {
                    if(!DB::table('software_project_technologies')->insert([
                        'project_id' => $project->id,
                        'technology_id' => $id,
                    ]))
                    {
                        // Log error
                        $technology_name = $technology['name'];
                        Log::error("Failed to save technology $technology_name to project $project->name", [
                            'project->id' => $project->id,
                            'technology_id' => $id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.software.add')->with([
            'success_alert' => "Created new software project $project->name",
        ]);
    }

    /**
     * Returns the software project form populated
     * with the data of the selected project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Projects $project)
    {
        // Load technologies associated with the software project
        $project->setTechnologiesArray();

        return view('admin.software.form')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Edit Project',
            'project' => $project,
        ]);
    }

    /**
     * Updates the properities and logo of 
     * a software project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, Projects $project)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Update project required fields
            $project->name = $request->get('name');
            $project->type = $request->get('type');
            $project->desc = $request->get('desc');
            $project->app_link = $request->get('app-link');

            // Check if it has a private codebase
            if(!$request->has('private-codebase'))
            {
                $project->codebase_link = $request->get('codebase-link');
            }

            if($request->has('logo'))
            {
                // Upload logo
                $project->logo = $request->file('logo')->store('public/images/software');
                $project->logo = str_replace('public/images/software/', '', $project->logo);
            }

            // Save the project
            if(!$project->save())
            {
                // Log errors
                Log::error('Failed to update software project', [
                    'project' => $project->toArray(),
                    'request' => $request->all(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to update the software project, see logs',
                ]);
            }

            // Clear the technologies relationship table
            DB::table('software_project_technologies')->where('project_id', $project->id)->delete();
            
            // Add relationships for all of the technologies
            foreach(config('software.technologies') as $id => $technology)
            {
                if($request->has("technology-$id"))
                {
                    if(!DB::table('software_project_technologies')->insert([
                        'project_id' => $project->id,
                        'technology_id' => $id,
                    ]))
                    {
                        // Log error
                        $technology_name = $technology['name'];
                        Log::error("Failed to save technology $technology_name to project $project->name", [
                            'project->id' => $project->id,
                            'technology_id' => $id,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.software.edit', ['project' => $project->id])->with([
            'success_alert' => "Updated software project $project->name",
        ]);
    }

    /**
     * Soft deletes a software project
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Projects $project)
    {
        // Verify user is admin authenticated
        if(\Auth::check())
        {
            // Attempt to soft delete project
            if(!$project->delete())
            {
                // Log errors
                Log::error('Failed to delete software project', [
                    'project' => $project->toArray(),
                ]);

                // Return errors
                return redirect()->back()->with([
                    'failure_alert' => 'Failed to delete software project, see logs',
                ]);
            }
        }

        return redirect()->route('admin.software.add')->with([
            'success_alert' => "Successfully deleted software project $project->name",
        ]);
    }
}
