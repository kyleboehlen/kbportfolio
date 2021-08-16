<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Models
use App\Models\User;
use App\Models\Software\Projects;

class PortfolioTest extends TestCase
{
    /**
     * Tests the main splash page
     *
     * @return void
     */
    public function test_home()
    {
        // Call home page
        $response = $this->get('/');

        $response->assertStatus(200);

        // Verify we see each portfolio option
        $response->assertSee('Photography');
        $response->assertSee('Software');
        $response->assertSee('Resume');
        $response->assertSee('Contact');
    }

    /**
     * Tests the software portfolio view
     *
     * @return void
     */
    public function test_software()
    {
        // Create test software projects
        $projects = Projects::factory(2)->create();

        // Call software view
        $response = $this->followingRedirects()->get(route('software'));

        $response->assertStatus(200);

        // Check that projects are showing
        foreach($projects as $project)
        {
            $response->assertSee($project->name);
        }
    }

    /**
     * Tests the contact page
     *
     * @return void
     */
    public function test_contact()
    {
        // Create test admin user it doesn't exist
        $users = User::all();
        if($users->count() == 0)
        {
            $user = User::factory()->create();
        }
        else
        {
            $user = User::first();
        }

        // Call contact view
        $response = $this->followingRedirects()->get(route('contact'));

        $response->assertStatus(200);

        // Check that contact info is showing
        $response->assertSee($user->phone_number);
        $response->assertSee($user->contact_email);
    }
}
