<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contact()
    {
        return redirect()->route('contact.details');
    }

    /**
     * Redirect to spotify playlists
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function spotify()
    {
        $spotify_user_id = config('admin.spotify.user_id');
        return redirect()->away("https://open.spotify.com/user/$spotify_user_id/playlists");
    }
}
