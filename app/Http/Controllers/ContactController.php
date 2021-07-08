<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

// Models
use App\Models\User;

// Requests
use App\Http\Requests\Contact\EmailRequest;
use App\Http\Requests\Contact\PhoneNumberRequest;

class ContactController extends Controller
{
    /**
     * Instantiate a new ContactController instance.
     */
    public function __construct()
    {
        // Set admin nav options
        $this->action_nav_opts = [
            [
                'text' => 'Details',
                'route' => 'admin.contact.details',
                'icon' => 'user-details',
            ],
            [
                'text' => 'Phone',
                'route' => 'admin.contact.edit.phone-number',
                'icon' => 'phone',
            ],
            [
                'text' => 'Email',
                'route' => 'admin.contact.edit.email',
                'icon' => 'email',
            ],
        ];

        // Get the admin user for contact details
        $this->user = User::all()->first();
    }

    /**
     * Okay stay with me here.. returns a view with the
     * contact details for the contact page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function details()
    {
        return view('contact')->with([
            'stylesheet' => 'contact',
            'user' => $this->user,
        ]);
    }

    /**
     * Allows you to view the contact details in
     * the admin panel
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminDetails()
    {
        return view('admin.contact.details')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Contact Details',
            'user' => $this->user,
        ]);
    }

    /**
     * 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editPhoneNumber()
    {
        return view('admin.contact.phone')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Update Phone #',
            'phone_number' => $this->user->phone_number,
        ]);
    }

    /**
     * Update the admin user's phone number which will
     * reflect on the contact page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePhoneNumber(PhoneNumberRequest $request)
    {
        // Update admin user's phone number
        if(\Auth::check())
        {
            $phone_number = $request->get('phone-number');
            $this->user->phone_number = $phone_number;
            $success = $this->user->save();
        }
        else
        {
            $success = true;
        }

        $blade_array = array();
        if($success)
        {
            $blade_array = [
                'success_alert' => 'Contact phone number updated',
            ];
        }
        else
        {
            // Log error
            Log::error('Failed to update contact phone number', [
                'phone_number' => $phone_number,
            ]);
        }

        return redirect()->route('admin.contact.details')->with($blade_array);
    }

    /**
     * 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editEmail()
    {
        return view('admin.contact.email')->with([
            'action_nav_opts' => $this->action_nav_opts,
            'card_header' => 'Update Email',
            'email' => $this->user->contact_email,
        ]);
    }

    /**
     * Update the admin user's contact email which will
     * reflect on the contact page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmail(EmailRequest $request)
    {
        // Update admin user's contact email
        if(\Auth::check())
        {
            $email = $request->get('email');
            $this->user->contact_email = $email;
            $success = $this->user->save();
        }
        else
        {
            $success = true;
        }

        $blade_array = array();
        if($success)
        {
            $blade_array = [
                'success_alert' => 'Contact email updated',
            ];
        }
        else
        {
            // Log error
            Log::error('Failed to update contact email', [
                'email' => $email,
            ]);
        }

        return redirect()->route('admin.contact.details')->with($blade_array);
    }
}
