<?php
return[
    // disk : images, public, s3. see filesystem configurations. 
    'disk'      => 's3',
    'prefix'    => "filemanager",
    // quality of image
    'quality'   => 75,
    'valid_image_mimetypes' => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
    ],
];