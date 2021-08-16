<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Models
use App\Models\User;
use App\Models\Software\Projects;

class SoftwareTest extends TestCase
{
    /**
     * Tests storing a new software project
     *
     * @return void
     */
    public function test_store()
    {
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

        // Test the add form route
        $response = $this->actingAs($user)->get(route('admin.software.add'));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.software.store'), [
            '_token' => csrf_token(),
            'name' => 'Test',
            'type' => 'demo',
            'logo' => new \Illuminate\Http\UploadedFile(storage_path('app/public/images/klogo.png'), 'logo.png', null, null, true),
            'desc' => 'Testing testing 1 2 3...',
            'private-codebase' => 'checked',
            'app-link' => 'https://www.kyleboehlen.com',
            'technology-1' => 'checked',
            'technology-2' => 'checked',
        ]);
        
        $project = Projects::orderBy('id', 'desc')->first();
        $project->setTechnologiesArray();
        $this->assertEquals($project->name, 'Test');
        $this->assertEquals($project->type, 'demo');
        $this->assertEquals($project->desc, 'Testing testing 1 2 3...');
        $this->assertEquals($project->app_link, 'https://www.kyleboehlen.com');
        $this->assertTrue(in_array(1, $project->technologies));
        $this->assertTrue(in_array(2, $project->technologies));
    }

    /**
     * Tests updating an exsisting software project
     *
     * @return void
     */
    public function test_update()
    {
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

        // Get project
        $project = Projects::first();
        if(is_null($project))
        {
            $project = Projects::factory()->create();
        }

        // Test the edit form route
        $response = $this->actingAs($user)->get(route('admin.software.edit', ['project' => $project->id]));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.software.update', ['project' => $project->id]), [
            '_token' => csrf_token(),
            'name' => 'Test',
            'type' => 'demo',
            'logo' => new \Illuminate\Http\UploadedFile(storage_path('app/public/images/klogo.png'), 'logo.png', null, null, true),
            'desc' => 'Testing testing 1 2 3...',
            'private-codebase' => 'checked',
            'app-link' => 'https://www.kyleboehlen.com',
        ]);
        
        $project->refresh();
        $this->assertEquals($project->name, 'Test');
        $this->assertEquals($project->type, 'demo');
        $this->assertEquals($project->desc, 'Testing testing 1 2 3...');
        $this->assertEquals($project->app_link, 'https://www.kyleboehlen.com');
    }

    /**
     * Tests deleting a software project
     *
     * @return void
     */
    public function test_delete()
    {
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

        // Get project
        $project = Projects::first();
        if(is_null($project))
        {
            $project = Projects::factory()->create();
        }

        // Test deleting it
        $response = $this->actingAs($user)->post(route('admin.software.destroy', ['project' => $project->id]), [
            '_token' => csrf_token(),
        ]);
        
        $project->refresh();
        $this->assertNotNull($project->deleted_at);
    }
}