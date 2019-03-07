<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 7/8/18
 * Time: 12:14 PM
 */

namespace App\lib\utils;

use Klein\Request;
use Delight\FileUpload\FileUpload;

class FileUploader
{
    /**
     * @var Request
     */
    private $_request = null;

    /**
     * @var FileUpload
     */
    private $_FILE_UPLOAD = null;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        try{
            $this->_FILE_UPLOAD = new FileUpload();
        }catch (\Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * Upload a single file
     * @param string $fileInputName
     * @param null $folderPath
     * @return bool|\Delight\FileUpload\File
     */
    public function store($fileInputName='file', $folderPath = null){
        $folderPath = is_null($folderPath) ? env('PUBLIC_UPLOADS_PATH_ROOT') : $folderPath;

        try{
//            $this->_FILE_UPLOAD->withMaximumSizeInMegabytes(20);
            $this->_FILE_UPLOAD->withTargetDirectory($folderPath);
            $this->_FILE_UPLOAD->from($fileInputName);
            return $this->_FILE_UPLOAD->save();
        }catch (\Delight\FileUpload\Throwable\InputNotFoundException $e) {
            // input not found
        }
        catch (\Delight\FileUpload\Throwable\InvalidFilenameException $e) {
            // invalid filename
        }
        catch (\Delight\FileUpload\Throwable\InvalidExtensionException $e) {
            // invalid extension
        }
        catch (\Delight\FileUpload\Throwable\FileTooLargeException $e) {
            // file too large
        }
        catch (\Delight\FileUpload\Throwable\UploadCancelledException $e) {
            // upload cancelled
        }
        catch (\Exception $exception){

        }
        return false;
    }

    public function getTargetFilename(){
        return $_FILES['file']['name'];
    }
}