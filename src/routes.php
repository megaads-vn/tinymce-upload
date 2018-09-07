<?php

Route::group(['middleware' => 'upload'], function () {
    Route::post('/upload/tinymce', '\Megaads\TinymceUpload\Controllers\Services\UploadService@tinymceUpload');
});
