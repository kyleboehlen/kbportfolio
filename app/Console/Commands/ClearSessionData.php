<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearSessionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all of the app session data.';

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
        // Get the default driver in order to figure out how we clear that data
        $session_driver = session()->getDefaultDriver();

        switch($session_driver)
        {
            case 'file':
                $file_path = storage_path('framework/sessions/'); // The path to the session data
                $command = "rm -f $file_path*"; // Command to delete the file session data
                exec($command);
                break;
        }
    }
}
