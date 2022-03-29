<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class SyncStaticAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:sync-static';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs commited static assets to an S3 bucket.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get all the static directories we need to sync
        $directories = config('filesystems.dir.static');

        $dir_count = count($directories);
        if ($dir_count > 0) {
            echo "Syncing static assets for $dir_count directories...\n";
        } else {
            echo "No asset directories to sync.\n";
            return 0;
        }

        foreach ($directories as $dir) {
            // Get the relative path of the directory
            $dir_path = config('filesystems.dir.' . $dir);

            // Get all the local files in that dir
            $local_files = Storage::disk('local')->files($dir_path);

            // And all the s3 files in that dir
            $s3_files = Storage::files($dir_path);

            // Delete any files on s3 that aren't in the local files
            foreach ($s3_files as $s3_file) {
                if (!in_array($s3_file, $local_files)) {
                    Storage::delete($s3_file);
                }
            }

            // Get the update s3 file list
            $s3_files = Storage::files($dir_path);
            
            // For all the local files we'll sync them to s3
            foreach ($local_files as $local_file) {
                // Delete the file if it already exists to make sure we have the new one, it'll be fine behind the CDN
                if (in_array($local_file, $s3_files)) {
                    Storage::delete($local_file);
                }

                // Stream the copy to s3
                Storage::writeStream($local_file, Storage::disk('local')->readStream($local_file));
            }
        }

        echo "Synced static assets!\n";

        return 0;
    }
}
