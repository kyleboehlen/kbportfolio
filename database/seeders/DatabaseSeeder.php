<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Models
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seed the admin user
        if(User::all()->count() == 0)
        {
            $admin_user = new User([
                'name' => config('admin.user.name'),
                'email' => config('admin.user.email'),
                'contact_email' => config('admin.user.email'),
                'phone_number' => config('admin.user.phone_number'),
                'password' => 'fuck off',
            ]);
        }
        else // Update the admin user
        {
            $admin_user = User::all()->first();
            $admin_user->name = config('admin.user.name');
            $admin_user->email = config('admin.user.email');
        }

        $admin_user->save();
    }
}
