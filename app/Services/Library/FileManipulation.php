<?php

namespace App\Services\Library;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class FileManipulation
{

    /**
     * [getFolderNameSaveFile 'get name folder save']
     * @param  [string] $folderName
     * @return [string]
     */
    private function getPathFile($fileName = null, $folderName = null)
    {
        if (!$folderName){
            $folderName = Config::get('filesystems.folder_name_save_files');
        }

        return join(DIRECTORY_SEPARATOR, [ $folderName, $fileName ]);
    }

    /**
     * [getRootPath 'get full path file']
     */
    private function getRootPath()
    {
        $default = Config::get('filesystems.default');
        return in_array($default, [ 'local', 'public' ]) ? Config::get("filesystems.disks.$default.root") : null;
    }

    /**
     * [getFullPathFile 'get full path file with path root']
     */
    private function getFullPathFile($fileName, $folderName)
    {
        $pathFile = $this->getPathFile($fileName, $folderName);
        $pathRoot = $this->getRootPath();
        $pathFull = join(DIRECTORY_SEPARATOR, [ $pathRoot, $pathFile ]);
        return $pathFull;
    }

    /**
     * [isFileEncodeBase64 'check if file is base64 encode']
     * @param  [mixed] $file
     * @return [bool]
     */
    public function isFileEncodeBase64($file)
    {
        $base64Test = explode(';base64,' , $file);

        return (bool)preg_match('`^[a-zA-Z0-9+/]+={0,2}$`', end($base64Test));
    }

    /**
     * [save 'save file base64 or file']
     * @param  [string] $fileNameSave

     * @param  [string | file] $file
     * @param  [string] $fileNameSave
     * @param  [string] $folderName
     * @param  [string] $visibility
     * @return [bool | pathName]
     */
    public function save($file, $fileNameSave, $folderName = null, $visibility = 'public')
    {
        if (empty($fileNameSave)){
            throw new Exception("File Name is Required");
        }

        if (Storage::exists($this->getPathFile($fileNameSave, $folderName))){
            throw new Exception("File Name already exists");
        }

        if ($this->isFileEncodeBase64($file)){
            $path = $this->getPathFile($fileNameSave, $folderName);

            if (Storage::put($path, base64_decode($file), $visibility)){
                return $path; //result all path file
            }
        } else if (Storage::putFileAs($this->getPathFile(), $file, $fileNameSave)){
            return $this->getPathFile($fileNameSave, $folderName, $visibility); //result all path file
        }

        return false;
    }

    private function checkExistsFile($fileName, $folderName)
    {
        if (empty($fileName) || !Storage::exists($this->getPathFile($fileName, $folderName))){
            throw new Exception("File does not exist");
        }
    }

    /**
     * [delete 'delete file by name']

     * @param  [string] $fileNameDel
     * @param  [string] $folderName
     * @return [bool]
     */
    public function delete($fileNameDel, $folderName = null)
    {
        $this->checkExistsFile($fileNameDel, $folderName);

        return Storage::delete($this->getPathFile($fileNameDel, $folderName));
    }

    /**
     * [download 'download file for web']
     * @param  [string] $fileName
     * @param  [string] $renameFile
     * @param  [string] $folderName
     * @return [response | download]
     */
    public function download($fileName, $renameFile = null, $folderName = null)
    {
        $this->checkExistsFile($fileName, $folderName);

        $renameFile = $renameFile ? $renameFile : $fileName;

        $headers = [   ];

        $pathFull = $this->getFullPathFile($fileName, $folderName);

        return response()->download($pathFull, $renameFile, $headers);
    }

    /**
     * [retrievingFile 'retrieving file from local server']
     * @param  [string] $fileNames
     * @param  [string] $folderNames
     * @return [file]
     */
    public function retrievingFile($fileName, $folderName = null)
    {
        $this->checkExistsFile($fileName, $folderName);

        return Storage::get($this->getPathFile($fileName, $folderName));
    }

    /**
     * [url 'generate a url file']
     * @param  [string] $fileName
     * @param  [string] $folderName
     * @return [string | url]
     */
    public function url($fileName, $folderName = null)
    {
        $this->checkExistsFile($fileName, $folderName);

        $url = Storage::url($this->getPathFile($fileName, $folderName));

        return urldecode($url);
    }

    /**
     * [metadataFile 'get meta file saved local']
     * @param  [string] $fileName
     * @param  [string] $folderName
     * @return [array]
     */
    public function metaDataFile($fileName, $folderName = null)
    {
        $this->checkExistsFile($fileName, $folderName);

        $metaData = Storage::getMetaData($this->getPathFile($fileName, $folderName));

        return array_merge($metaData, [
            'lastModified' => Storage::lastModified($this->getPathFile($fileName, $folderName)),
            'mimeType' => Storage::mimeType($this->getPathFile($fileName, $folderName))
        ]);
    }
}
