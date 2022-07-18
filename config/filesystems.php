<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path() . 'app/',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'minio' => [
            'driver' => 's3',
            'key' => 'sail',
            'secret' => 'password',
            'region' => 'us-east-1',
            'bucket' => 'local',
            'url' => 'http://localhost:9000/local/app',
            'endpoint' => 'http://minio:9000',
            'use_path_style_endpoint' => true,
            // 'bucket_endpoint' => true,
        ],

    ],

    // Directories for referencing files
    'dir' => [
        'documents' => env('DO_FOLDER') . 'public/documents/',
        'icons' => env('DO_FOLDER') . 'public/icons/',
        'images' => env('DO_FOLDER') . 'public/images/',
        'software' => env('DO_FOLDER') . 'public/images/software/',
        'discord' => env('DO_FOLDER') . 'public/images/discord/',
        'photography' => [
            'compressed' => env('DO_FOLDER') . 'public/images/photography/compressed/',
            'fullres' => env('DO_FOLDER') . 'public/images/photography/fullres/',
        ],
        // The directories that are static assets that need to be synced with the s3 bucket
        'static' => [
            'icons', 'images',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [],

];
