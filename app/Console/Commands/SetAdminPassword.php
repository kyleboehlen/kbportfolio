<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\User;

class SetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows you to set the password of the admin user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password = $this->secret('What is the password?');
        $admin_user = User::all()->first();
        $admin_user->password = Hash::make($password);
        $admin_user->save();
    }
}
