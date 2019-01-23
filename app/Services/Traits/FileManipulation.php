<?php

namespace App\Services\Traits;

use App\Services\Library\FileManipulation as FileModuleManipulation;
use App\Models\Files;
use Exception;
use Intervention\Image\Facades\Image;

trait FileManipulation {

    /**
     * [generateModuleName 'generate name module by class']
     * @return [string] [description]
     */
    private function generateModuleName()
    {
        return __CLASS__;
    }

    /**
     * [getFolderName 'result folder name for save if exists']
     * @return [string]
     */
    private function getFolderName($customFolderName = null)
    {
        if ($customFolderName){
            return $customFolderName;
        }

        if (isset($this->files['folderName'])){
            return $this->files['folderName'];
        }

        return null;
    }

    /**
     * [generateUniqueNameFile 'create unique name for file']
     * @param  [string] $fileOriginalName
     * @return [string]
     */
    private function generateUniqueNameFile($fileOriginalName)
    {
        return md5(uniqid(date('Y-m-d H:i:s:u'))) . '.' . pathinfo($fileOriginalName, PATHINFO_EXTENSION);
    }

    /**
     * [getRealFile 'can array or object uploadfile']
     */
    private function getRealFile($file)
    {
        $fileString = is_array($file) ? $file['File'] : $file;

        $base64Test = explode(';base64,' , $fileString);

        return end($base64Test);
    }

    /**
     * [getInformationFile 'customize response']
     * @param  [type] $manipulation
     * @param  [array | UploadFile] $file
     * @return [array]
     */
    private function getInformationFile($manipulation, $file)
    {
        $result = [
            'ModuleName' => $this->generateModuleName(),
            'IdentifierModule' => $this->{ $this->primaryKey },
        ];

        $result = is_array($file) ? array_merge($file, $result) : $result;

        $result['File'] = $this->getRealFile($file);

        if ($manipulation->isFileEncodeBase64($result['File'])){

            $result['Name'] = $this->generateUniqueNameFile($file['OriginalName']);

        } else {
            $result['Name'] = $this->generateUniqueNameFile($file->getClientOriginalName());
            $result['OriginalName'] = $file->getClientOriginalName();
            $result['MimeType'] = $file->getClientMimeType();
            $result['Size'] = $file->getClientSize();
            $result['File'] = $file;
        }

        return $result;
    }

    /**
     * [isExtensionImage get true if type is image]
     * @param  string  $extension
     * @return boolean
     */
    private function isExtensionImage(string $extension)
    {
        $extensions = ['jpeg','jpg','gif','png','bmp'];
        return in_array($extension, $extensions);
    }

    /**
     * [saveFileLocal 'save file on local server']
     * @param  [FileModuleManipulation] $manipulation
     * @param  [array] $information
     * @return [bool]
     */
    private function saveFileLocal($manipulation, $information, $folderName)
    {
        try {
            $extension = pathinfo($information['OriginalName'], PATHINFO_EXTENSION);

            /**
             * Utilizo a classe image para deixar o tamanho final pequena
             */
            if ($this->isExtensionImage($extension)){
                $imageObject = Image::make($information['File']);
                $encode = base64_encode((string)$imageObject->encode($extension, 60)); // 60% de qualidade

                $information['File'] = $encode;
            }

            return $manipulation->save($information['File'], $information['Name'], $folderName);
        } catch (Exception $e) {
            debug([ 'error FileManipulation' => $e->getMessage() ]);
            return false;
        }
    }

    private function validateKey()
    {
        if (empty($this->{ $this->primaryKey })){
            throw new Exception("Key model not found");
        }
    }

    private function createManipulation($fileId = null)
    {
        $this->validateKey();
        return new FileModuleManipulation();
    }

    /**
     * [storeFileDb 'save information file on database']
     * @param  [array] $information
     * @return [bool]
     */
    public function storeFileDb($information)
    {
        try {

            if (!empty($information['FileId'])){
                $files = Files::find($information['FileId']);
                $files->fill($information);
                return $files->save();
            }

            return Files::create($information);

        } catch (Exception $e) {
            debug([ 'error FileManipulation' => $e->getMessage() ]);
            return false;
        }
    }

    /**
     * [saveFile 'save file local and DataBase']
     * @param  [array | UploadFile] $file
     * @return [mixed]
     */
    public function saveFile($file, $folderName = null, $fileId = null)
    {
        $manipulation = $this->createManipulation();

        $information = $this->getInformationFile($manipulation, $file);

        $folderName = $this->getFolderName($folderName);

        if ($this->saveFileLocal($manipulation, $information, $folderName)){
            unset($information['File']);

            $metaDataFileSaved = $manipulation->metaDataFile($information['Name'], $folderName);

            $information['Size'] = $metaDataFileSaved['size'];
            $information['MimeType'] = $metaDataFileSaved['mimeType'];

            if ($fileSaved = $this->storeFileDb($information)){
                return $fileSaved;
            }
        }

        throw new Exception("Error on save File");
    }

    /**
     * [getFileRecordsDataBase 'all recods file database']
     * @param  [int] $fileId
     * @return [model]
     */
    private function getFileRecordsDataBase($fileId = null)
    {
        $query =  Files::where('ModuleName', $this->generateModuleName())
            ->where('IdentifierModule', $this->{ $this->primaryKey });

        if ($fileId){
            $query->where('FileId', $fileId);

            if (!$query->count()){
                throw new Exception("File id {$fileId} not found");
            }
        }

        return $query->get();
    }

    /**
     * [deleteFileLocal 'remove file local server']
     * @param  [array] $manipulation
     * @param  [model] $fileDb
     * @return [bool]
     */
    private function deleteFileLocal($manipulation, $fileDb, $folderName)
    {
        try {
            return $manipulation->delete($fileDb->Name, $folderName);
        } catch (Exception $e) {
            debug([ 'error FileManipulation' => $e->getMessage() ]);
            return false;
        }
    }

    /**
     * [deleteFile 'delete file local and DataBase']
     * @param  [int] $fileId
     * @return [array]
     */
    public function deleteFile($fileId = null, $folderName = null)
    {
        $manipulation = $this->createManipulation();

        $records = $this->getFileRecordsDataBase($fileId);
        $deleted = [];

        foreach ($records as $record) {
            if (!$this->deleteFileLocal($manipulation, $record, $this->getFolderName($folderName))){
                throw new Exception("Error on delete file local");
            }

            if (!$record->delete()){
                throw new Exception("Error on delete file DataBase");
            }

            $deleted[] = $record;
        }

        return $deleted;
    }

    /**
     * [downloadFile 'download file for web']
     * @param  [int] $fileId
     * @return [response download]
     */
    public function downloadFile($fileId = null, $folderName = null)
    {
        $manipulation = $this->createManipulation();

        $record = $this->getFileRecordsDataBase($fileId);

        if ($record->count() == 0){
            throw new Exception("File to download not exists");
        }

        return $manipulation->download($record[0]->Name, $record[0]->OriginalName, $this->getFolderName($folderName));
    }

    /**
     * [files description]
     * @param  [type] $fileId [description]
     * @return [type]         [description]
     */
    public function files($fileId = null, $withBase64 = false, $folderName = null)
    {
        $manipulation = $this->createManipulation();
        $records = $this->getFileRecordsDataBase($fileId);

        return array_map(function($record) use ($withBase64, $manipulation, $folderName){
            unset($record['IdentifierModule'], $record['ModuleName'], $record['Size']);

            if ($withBase64){
                $record['Base64'] = base64_encode($manipulation->retrievingFile($record['Name'], $this->getFolderName($folderName)));
            }

            return $record;
        }, $records->toArray());
    }

    /**
     * [generateUrl 'generate url']
     * @param  [int]
     * @return [string | url]
     */
    public function generateUrl($fileId, $folderName = null)
    {
        $manipulation = $this->createManipulation();
        $record = $this->getFileRecordsDataBase($fileId)->first();

        return $manipulation->url($record->Name, $this->getFolderName($folderName));
    }
}
