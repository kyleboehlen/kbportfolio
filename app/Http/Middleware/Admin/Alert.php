<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;

class Alert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // If the session does not have the admin alert flag
        if(!session()->has('admin_alert'))
        {
            // Set the admin alert to true for the first request of the session
            session(['admin_alert' => true]);
        }
        
        return $next($request);
    }
}
