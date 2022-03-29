<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

// Models
use App\Models\Photography\Photos;
use App\Models\Software\Projects;

class PurgeDeletedAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assets:purge-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges deleted assets from the ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $total_assets_purged = 0;

        // get all the asset names for photos and software project logos so we don't keep hitting the DB
        $software_assets = Projects::all()->pluck('logo')->toArray();
        $photography_assets = Photos::all()->pluck('asset')->toArray();

        // Config dirs to purge deleted assets from
        $directories = [
            'software', 'photography.compressed', 'photography.fullres',
        ];

        echo "Purging deleted assets...\n";

        foreach ($directories as $dir) {
            $assets_purged = 0;

            // Use the photography assets array to verify whether something needs to be purged 
            $asset_names = str_contains($dir, 'photography') ? $photography_assets : $software_assets;

            // Get the full dir path from config and get all assets in that dir
            $dir_path = config('filesystems.dir.' . $dir);
            $assets = Storage::files($dir_path);

            // Purge any assets that have been deleted
            foreach ($assets as $asset) {
                $asset_name = str_replace($dir_path, '', $asset);

                // Check if it exists in the assets anymore
                if (!in_array($asset_name, $asset_names)) {
                    Storage::delete($asset);
                    $assets_purged++;
                }
            }

            $total_assets_purged += $assets_purged; // Keep total running count
            echo "Purged $assets_purged assets from $dir_path...\n";
        }

        echo "Completed, purged $total_assets_purged total assets.\n";

        return 0;
    }
}
