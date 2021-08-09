<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// Models
use App\Models\User;
use App\Models\Photography\Shoots;

class PhotographyTest extends TestCase
{
    /**
     * Tests creating a new shoot
     *
     * @return void
     */
    public function test_store_shoot()
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
        $response = $this->actingAs($user)->get(route('admin.photography.shoot.add'));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.photography.shoot.store'), [
            '_token' => csrf_token(),
            'name' => 'Test',
            'date' => '2021-08-01',
            'desc' => 'Testing testing 1 2 3...',
            'category-1' => 'checked',
            'category-2' => 'checked',
        ]);
        
        $shoot = Shoots::orderBy('id', 'desc')->first();
        $shoot->setCategoriesArray();
        $this->assertEquals($shoot->name, 'Test');
        $this->assertEquals($shoot->desc, 'Testing testing 1 2 3...');
        $this->assertEquals($shoot->shot_on, '2021-08-01');
        $this->assertTrue(in_array(1, $shoot->categories));
        $this->assertTrue(in_array(2, $shoot->categories));
    }

    /**
     * Tests updating an exsisting shoot
     *
     * @return void
     */
    public function test_update_shoot()
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

        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }

        // Test the edit form route
        $response = $this->actingAs($user)->get(route('admin.photography.shoot.edit', ['shoot' => $shoot->id]));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.photography.shoot.update', ['shoot' => $shoot->id]), [
            '_token' => csrf_token(),
            'name' => 'Test',
            'date' => '2021-08-01',
            'desc' => 'Testing testing 1 2 3...',
            'category-1' => 'checked',
            'category-2' => 'checked',
        ]);
        
        $shoot->refresh();
        $shoot->setCategoriesArray();
        $this->assertEquals($shoot->name, 'Test');
        $this->assertEquals($shoot->desc, 'Testing testing 1 2 3...');
        $this->assertEquals($shoot->shot_on, '2021-08-01');
        $this->assertTrue(in_array(1, $shoot->categories));
        $this->assertTrue(in_array(2, $shoot->categories));
    }

    /**
     * Tests deleting a shoot
     *
     * @return void
     */
    public function test_delete_shoot()
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

        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }

        // Test deleting it
        $response = $this->actingAs($user)->post(route('admin.photography.shoot.destroy', ['shoot' => $shoot->id]), [
            '_token' => csrf_token(),
        ]);
        
        $shoot->refresh();
        $this->assertNotNull($shoot->deleted_at);
    }

    /**
     * Tests uploading a photo
     *
     * @return void
     */
    public function test_upload_photo()
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

        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }
        $shoot->load('photos');
        $photo_count = $shoot->photos->count();

        // Test the upload form route
        $response = $this->actingAs($user)->get(route('admin.photography.photos.upload'));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.photography.photo.store', ['shoot' => $shoot->id]), [
            '_token' => csrf_token(),
            'file' => new \Illuminate\Http\UploadedFile(storage_path('app/public/images/klogo.png'), 'logo.png', null, null, true),
        ]);
        
        $shoot->refresh();
        $shoot->load('photos');
        $this->assertEquals($photo_count + 1, $shoot->photos->count());
    }

    /**
     * Tests updating a photo
     *
     * @return void
     */
    public function test_update_photo()
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

        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }

        $shoot->load('photos');

        if($shoot->photos->count() == 0)
        {
            $photo = Photos::factory()->create();
        }
        else
        {
            $photo = $shoot->photos->first();
        }

        // Test the edit form route
        $response = $this->actingAs($user)->get(route('admin.photography.photos.edit', ['shoot' => $shoot->id]));
        $response->assertStatus(200);

        // Test the store route
        $response = $this->actingAs($user)->post(route('admin.photography.photo.update', ['photo' => $photo->id]), [
            '_token' => csrf_token(),
            'caption' => 'Test',
            'show-on-home' => 1,
            'category-1' => 'checked',
            'category-2' => 'checked',
        ]);
        
        $photo->refresh();
        $photo->setCategoriesArray();
        $this->assertEquals($photo->caption, 'Test');
        $this->assertEquals($photo->show_on_home, 1);
        $this->assertTrue(in_array(1, $photo->categories));
        $this->assertTrue(in_array(2, $photo->categories));
    }

    /**
     * Tests deleting a photo
     *
     * @return void
     */
    public function test_delete_photo()
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

        // Get shoot
        $shoot = Shoots::first();
        if(is_null($shoot))
        {
            $shoot = Shoots::factory()->create();
        }

        $shoot->load('photos');

        if($shoot->photos->count() == 0)
        {
            $photo = Photos::factory()->create();
        }
        else
        {
            $photo = $shoot->photos->first();
        }

        // Test deleting it
        $response = $this->actingAs($user)->post(route('admin.photography.photo.destroy', ['photo' => $photo->id]), [
            '_token' => csrf_token(),
        ]);
        
        $photo->refresh();
        $this->assertNotNull($photo->deleted_at);
    }
}