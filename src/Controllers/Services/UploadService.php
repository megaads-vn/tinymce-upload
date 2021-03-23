<?php
/**
 * Created by PhpStorm.
 * User: tuanpa
 * Date: 4/7/17
 * Time: 3:13 PM
 */

namespace Megaads\TinymceUpload\Controllers\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Storage;

class UploadService extends \App\Http\Controllers\Services\BaseService
{


    protected function validator($file)
    {
        $message = '';
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
        );

        $validator = \Validator::make(['image' => $file], $rules);

        if ($validator->fails()) {
            $message = $validator->errors()->getMessages();
        }
        return $message;
    }

    /**
     * Create a directory.
     *
     * @param  string $path
     * @param  int $mode
     * @param  bool $recursive
     * @param  bool $force
     * @return bool
     */
    protected function makeDirectory($path, $mode = 0777, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        } else {
            return mkdir($path, $mode, $recursive);
        }
    }

    public function tinymceUpload(Request $request){
        $localePath = $this->getRouter()->getCurrentRoute()->getPrefix();
        $result = $this->getDefaultStatus();
        $file = $request->file('file');
        if ($message = $this->validator($file)) {
            $result['message'] = $message;
            return response()->json($result);
        }
        $storage =  Storage::disk('images');
        $directoryPath = config('filesystems.disks.images.root');
        $relativePath = config('filesystems.disks.images.relative');
        if ($localePath != "") {
            $extractPath = explode("/", $directoryPath);
            $toPublic = '';
            $afterPublic = '';
            foreach ($extractPath as $index => $item) {
                if ($item !== '') {
                    if ($item == 'public') {
                        $toPublic .= '/' . $item;
                        unset($extractPath[$index]);
                        break;
                    }
                    $toPublic .= '/' . $item;
                    unset($extractPath[$index]);
                }
                
            }
            $afterPublic = join('/', $extractPath);
            $directoryPath = $toPublic . '/' . $localePath . $afterPublic; 
        }
        try {
            $imageName = $request->get('fileName', '');
            if ($imageName) {
                $fileNameArr = explode('-', $imageName);
                $imageName = $fileNameArr[0];
            }
            $customDirectoryPath = $request->get('customDirectoryPath');
            if ($customDirectoryPath) {
                if (strpos($customDirectoryPath,"store/")!==false) {
                    $customDirectoryPath = "stores/";
                }else if(strpos($customDirectoryPath,"coupon/")!==false){
                    $customDirectoryPath = "coupon/";
                }
                $directoryPath = $directoryPath . '/' . $customDirectoryPath;
                if (!$storage->exists($customDirectoryPath)) {
                    $storage->makeDirectory($customDirectoryPath);
                }
            }
            //$imageName = $file->getClientOriginalName();
            $imageNewName = $imageName . '_' . microtime(true) . '.' . $file->getClientOriginalExtension();
            $imageNewName = strtolower($imageNewName);
            $file->move($directoryPath, strtolower($imageNewName));
            $result = $this->getSuccessStatus();
            $fullRelativePath = $relativePath . $imageNewName;
            
            if($customDirectoryPath){
                $fullRelativePath = $relativePath . $customDirectoryPath . '/' . $imageNewName;
            }
            $result['result']['large'] = $fullRelativePath;
            $result['result']['thumb'] = $fullRelativePath;

            $location = 'https://' . $_SERVER['HTTP_HOST'] . (($localePath != '') ? '/'. $localePath : '') . $fullRelativePath;
            $result = [
                'location' => $location
            ];


        } catch (\Exception $ex) {
            $result['message'] = $ex->getMessage();
        }

        return response()->json($result);
    }

}
