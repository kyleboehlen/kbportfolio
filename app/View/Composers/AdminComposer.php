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
        $view_array = [
            'route' => Route::currentRouteName(),
            'stylesheet' => 'admin',
        ];

        if(session()->get('admin_alert'))
        {
            $admin_alert = array();

            if(\Auth::check())
            {
                $name = \Auth::user()->name;
                $admin_alert['title'] = 'Hey ' . substr($name, 0, strpos($name, ' ')) . '!';
                $admin_alert['body'] = config('compliment')[array_rand(config('compliment'))];
                $admin_alert['icon'] = 'success';
            }
            else
            {
                $admin_alert['title'] = 'Welcome!';
                $admin_alert['body'] = 'It appears you\'ve found my admin panel, feel free to poke around and take a look at what I\'ve done here. As a guest any changes you make will not persist. Have fun :)';
                $admin_alert['icon'] = 'info';
            }

            $view_array['admin_alert'] = $admin_alert;

            session(['admin_alert' => false]);
        }

        $view->with($view_array);
    }
}