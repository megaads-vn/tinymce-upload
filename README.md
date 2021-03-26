## LARAVEL UPLOAD IMAGE FOR TINYMCE

Upload image server side for tinymce custom plugin. Normally, when you custom image upload plugin for tinymce you'll create some code on tinymce config and some code on server for upload image and return the image link. This package help you on the server side, it will be receiver the upload request and save into directory, where you want to save image, then return the absolute url for your tinymce editor.

### INSTALL AND CONFIGURATION.

To install this package, just run command bellow: 

```
composer require megaads-vn/tinymce-upload
```

After install completed, open file app.php in config directory and add this line to providers section:

```
Megaads\TinymceUpload\TinymceUploadProvider::class,
```

Then go to `filesystems.php` in `config` directory and add this config to the `disks` section:

```
'images' => [
    'driver' => 'local',
    'root' => public_path('images'),
    'relative' => '/images/',
],
```
After all, run command `php artisan config:cache` and `php artisan cache:clear` to clear caches. You can call to bellow url for upload image to server.

```
//example.com/upload/tinymce
```
Or if your website is multiple languages and have the url like `//example.com/(us|uk|fr)` you still can call upload image with a tiny changing. Like this: 

```
//example.com/(us|uk|fr)/upload/tinymce
```

Thanks!