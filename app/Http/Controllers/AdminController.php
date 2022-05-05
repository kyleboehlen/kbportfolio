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
        return view('admin.index')->with(['card_header' => 'No place like 127.0.0.1 :)']);
    }

    /**
     * Redirect to add shoot
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function photography()
    {
        return redirect()->route('admin.photography.shoot.add');
    }

    /**
     * Redirect to view the add software project form
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function software()
    {
        return redirect()->route('admin.software.add');
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

    /**
     * Redirect to view the add discord bots form
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bots()
    {
        return redirect()->route('admin.bots.add');
    }
}
