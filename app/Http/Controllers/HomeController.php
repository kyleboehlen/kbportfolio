<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the splash page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('splash')->with(['stylesheet' => 'splash']);
    }

    /**
     * Show the contact page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contact()
    {
        // Get the admin user for contact details
        $user = User::all()->first();

        return view('contact')->with([
            'stylesheet' => 'contact',
            'user' => $user,
        ]);
    }
}
