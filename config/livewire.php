<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing them in a temporary directory
    | before the final model is saved. For Cloudflare R2 / S3 deployments,
    | temporary files MUST use the 'local' disk so the upload is handled
    | reliably by the web server without triggering S3 CORS / ACL errors.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => ['file', 'max:51200'], // Batas 50MB per file
        'directory' => 'livewire-tmp',
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma', 'pdf',
        ],
        'max_upload_time' => 10, // Menit sebelum token kadaluarsa
    ],

];
