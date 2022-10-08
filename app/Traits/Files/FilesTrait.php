<?php
namespace App\Traits\Files;

trait FilesTrait{

    function saveFiles(object $request, $fileStoragePath, $defaultFileName = null, $fileNameOnForm = null, $fileColumnNameOnDb = null, object $existingFileDatabaseObject = null, int $num = null): string{

        $columnNameOnDb = $fileColumnNameOnDb === null ? 'image': $fileColumnNameOnDb;
        $nameOnForm = $fileNameOnForm === null ? 'image': $fileNameOnForm;

        $fileNameToStore = $this->returnImageName($defaultFileName, $columnNameOnDb, $existingFileDatabaseObject);

        $fileIndexName = $num === null ? $nameOnForm : $nameOnForm.'.'.$num;

        if($request->hasFile($fileIndexName)){

            //delete the image that exist before if its found
            $this->deleteImage($fileStoragePath, $defaultFileName, $columnNameOnDb, $existingFileDatabaseObject);

            //get filename with the extension
            (string)$filenameWithExt = $request->file($fileIndexName)->getClientOriginalName();

            //get just file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            //get just extension
            $extension = $request->file($fileIndexName)->getClientOriginalExtension();

            //file name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            //upload image
            $path = $request->file($fileIndexName)->storeAs('public/'.$fileStoragePath, $fileNameToStore);

        }
        return $fileNameToStore;

    }

    //function for deleting the image
    private function deleteImage(string $fileStoragePath, string $defaultFileName, string $columnNameOnDb, object $selectedModelObject = null):void{
        if($selectedModelObject !== null){
            if ($selectedModelObject->$columnNameOnDb !== $defaultFileName) {
                if(file_exists(storage_path('app/public/'.$fileStoragePath) . $selectedModelObject->$columnNameOnDb)){
                    $file_old = storage_path('app/public/'.$fileStoragePath) . $selectedModelObject->$columnNameOnDb;
                    unlink($file_old);
                }
            }
        }
    }

    //returns the image name based on the db return and main default image name
    private function returnImageName(string $defaultFileName, string $columnNameOnDb, object $dbSelectedObject = null): string{
        $fileNameToStore = $defaultFileName;
        if($dbSelectedObject  !== null){
            $fileNameToStore = $dbSelectedObject->$columnNameOnDb === $defaultFileName ? $defaultFileName : $dbSelectedObject->$columnNameOnDb;
        }
        return $fileNameToStore;
    }

}
?>