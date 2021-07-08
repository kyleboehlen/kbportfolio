<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the splash page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.index');
    }
        
    /**
     * Redirect to the admin view resume function
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resume()
    {
        return redirect()->route('admin.resume.view');
    }

    /**
     * Redirect to the admin view contact details function
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact()
    {
        return redirect()->route('admin.contact.details');
    }
}
