<?php

$locale = Request::segment(1);
if ($locale == 'upload') {
    $locale = '';
}
Route::group(['middleware' => ['mce_upload', 'mce_cors'], 'prefix' => $locale], function () {
    Route::post('/upload/tinymce', '\Megaads\TinymceUpload\Controllers\Services\UploadService@tinymceUpload');
});
