<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class AdminComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Add paramaters to pass to the blade view
        $view_array = [
            'route' => Route::currentRouteName(), // Get the name of the current route for the blade template
            'stylesheet' => 'admin', // Set the stylesheet to use the admin stylesheet w/ bootstrap
        ];

        // Check if this is the first request of the session
        if(session()->get('admin_alert'))
        {
            $admin_alert = array();

            // If the user is admin authenticated
            if(\Auth::check())
            {
                $name = \Auth::user()->name; // Get the admin user's name
                $admin_alert['title'] = 'Hey ' . substr($name, 0, strpos($name, ' ')) . '!';
                $admin_alert['body'] = config('compliment')[array_rand(config('compliment'))]; // and get a random compliment for the admin user :)
                $admin_alert['icon'] = 'success';
            }
            else
            {
                // Set guest info alert for users who find my admin panel
                $admin_alert['title'] = 'Welcome!';
                $admin_alert['body'] = 'It appears you\'ve found my admin panel, feel free to poke around and take a look at what I\'ve done here. As a guest any changes you make will not persist. Have fun :)';
                $admin_alert['icon'] = 'info';
            }

            $view_array['admin_alert'] = $admin_alert;

            // Set the session alert flag so it doesn't get shown on future requests on this session
            session(['admin_alert' => false]);
        }

        $view->with($view_array);
    }
}