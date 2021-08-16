<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Models
use App\Models\User;

class ResumeTest extends TestCase
{
    /**
     * Tests updating the contact phone number
     *
     * @return void
     */
    public function test_phone()
    {
        // Set test phone number
        $test_phone = '9999999999';

        // Create test user if we need one
        $users = User::all();
        if($users->count() == 0)
        {
            $user = User::factory()->create();
        }
        else
        {
            $user = User::first();
        }

        // Call the view for updating phone number
        $response = $this->actingAs($user)->get(route('admin.contact.edit.phone-number'));
        $response->assertStatus(200);

        // Call update route
        $response = $this->actingAs($user)->post(route('admin.contact.update.phone-number'), [
            '_token' => csrf_token(),
            'phone-number' => $test_phone
        ]);

        // Verify it updated
        $user->refresh();
        $this->assertEquals($user->phone_number, $test_phone);
    }

    /**
     * Tests updating the contact email
     *
     * @return void
     */
    public function test_email()
    {
        // Set test email
        $test_email = 'test@testing.com';

        // Create test user if we need one
        $users = User::all();
        if($users->count() == 0)
        {
            $user = User::factory()->create();
        }
        else
        {
            $user = User::first();
        }

        // Call the view for updating email
        $response = $this->actingAs($user)->get(route('admin.contact.edit.email'));
        $response->assertStatus(200);

        // Call update route
        $response = $this->actingAs($user)->post(route('admin.contact.update.email'), [
            '_token' => csrf_token(),
            'email' => $test_email
        ]);

        // Verify it updated
        $user->refresh();
        $this->assertEquals($user->contact_email, $test_email);
    }
}