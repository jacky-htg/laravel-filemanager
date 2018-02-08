<?php

$middleware = [
    'web',
    '\Rebelworks\Filemanager\Middleware\FileManager'
];

$as = 'rebelworks.filemanager.';
$namespace = '\Rebelworks\Filemanager\Controllers';

Route::group(compact('middleware', 'prefix', 'as', 'namespace'), function () {
    Route::get('/filemanager', 'FileManagerController@index');
    Route::post('/filemanager', 'FileManagerController@index');
});