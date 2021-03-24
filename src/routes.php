<?php

$locale = Request::segment(1);
if ($locale == 'upload') {
    $locale = '';
}
Route::group(['middleware' => 'mce_upload', 'prefix' => $locale], function () {
    Route::post('/upload/tinymce', '\Megaads\TinymceUpload\Controllers\Services\UploadService@tinymceUpload');
});
