<?php

Route::group(['middleware' => 'upload'], function () {
    Route::post('/upload/tinymce', '\Megaads\Controllers\Services\UploadService@tinymceUpload');
});
