<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Requests
use App\Http\Requests\Resume\UpdateRequest;

class ResumeController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->action_nav_opts = [
            [
                'text' => 'View',
                'route' => 'admin.resume.view',
                'icon' => 'view',
            ],
            [
                'text' => 'Upload',
                'route' => 'admin.resume.edit',
                'icon' => 'upload',
            ],
        ];
    }

    /**
     * Return view to, okay get this, view the current resume...
     * ...with a pdf viewer... cuz it's a pdf... WOW!
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view()
    {
        return view('admin.resume.view')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'View Current Resume',
        ]);
    }

    /**
     * Return a view with the bootstrap upload file
     * input that allows a new doc to be uploaded 
     * to /storage/documents/resume.pdf
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editResume()
    {
        return view('admin.resume.upload')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Upload New Resume',
        ]);
    }

    /**
     * Uploads the document to /storage/documents/resume.pdf
     * and redirects back to the view
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateResume(UpdateRequest $request)
    {
        // Upload the resume
        if(\Auth::check())
        {
            $request->file('resume')->storeAs('public', 'documents/resume.pdf');
        }

        return redirect()->route('admin.resume.view')->with([
            'success_alert' => 'New resume uploaded',
        ]);
    }
}
