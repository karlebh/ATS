<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UtilityTrait
{
    private function processFiles($uploadedFiles, $model)
    {
        $files = [];

        foreach ($uploadedFiles as $file) {

            $temporaryFilePath = "tmp/{$file->folder}/{$file->filename}";
            $permanentFilePath = "uploads/{$file->folder}/{$file->filename}";

            if (! (Storage::disk('public')->exists($temporaryFilePath))) {
                $this->notFoundResponse(
                    'The file you wish to upload does not exists in a temporary storage and this might affect how filepond works'
                );
            }

            if (Storage::disk('public')->exists($temporaryFilePath)) {
                Storage::disk('public')->move($temporaryFilePath, $permanentFilePath);

                $files[] = "uploads/{$file->folder}/{$file->filename}";
                Storage::disk('public')->deleteDirectory("tmp/{$file->folder}");
                $file->delete();
            }
        }

        $mergedFiles = array_merge(
            $this->getFilesArray($model->files),
            $files
        );

        $model->update(['files' => $mergedFiles]);
    }

    public function getFilesArray($files)
    {
        if (is_array($files)) {
            return $files;
        } elseif (is_string($files)) {
            return json_decode($files, true) ?? [];
        }

        return [];
    }


    private function randomNumber()
    {
        $randomNumber = mt_rand(0, 99999);
        $formattedNumber = sprintf("%02d-%05d", $randomNumber / 10000, $randomNumber % 10000);

        return $formattedNumber;
    }

    private function deleteTemporaryUploadedFiles($files)
    {
        foreach ($files as $file) {

            $fileFolder = storage_path("/app/public/tmp/" . $file->folder);

            if (File::exists($fileFolder) &&  File::isDirectory($fileFolder)) {
                File::deleteDirectory($fileFolder);
            }

            $file->delete();
        }
    }
}
