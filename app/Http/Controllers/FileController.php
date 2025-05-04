<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\TemporaryFile;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Str;


class FileController extends Controller
{
    use ResponseTrait;

    public function upload(Request $request)
    {
        $request->validate([
            'file.*' => [
                'required',
                'file',
                'mimes:txt,csv,png,jpg,jpeg,gif,pdf',
                'max:30720'
            ],
        ]);

        $storedFiles = [];
        $uploadId = time() . Str::random(10);

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {

                $folder =  uniqid(true);
                $fileName = Str::random(10) . '-' . $file->getClientOriginalName();

                $path = $file->storeAs("tmp/{$folder}", $fileName, 'public');

                $storedFiles[] = $path;

                $temp =  TemporaryFile::create([
                    'user_id' => auth()->id(),
                    'filename' => $fileName,
                    'folder' => $folder,
                    'upload_id' => $uploadId,
                ]);
            }

            return $this->successResponse(
                message: "Please add the upload ID to the upload form as an hidden input or as a normal input if using API. Without it the file uploaded will not be attached to the form/models that needs it.THIS IS VERY IMPORTANT!!!",
                data: [
                    'message' => 'File(s) uploaded successfully!',
                    'files' => $storedFiles,
                    'upload_id' => $temp->upload_id,
                ]
            );
        }

        return $this->notFoundResponse(message: 'No file(s) selected');
    }

    public function deleteFromTempoarayStorage(int|string $uploadId)
    {

        $tempFiles = TemporaryFile::where('upload_id', $uploadId)->get();

        if (! $tempFiles) {
            return $this->notFoundResponse('The file(s) was not found!');
        }

        foreach ($tempFiles as $file) {
            $fileFolder = storage_path("/app/public/tmp/" . $file->folder);

            if (File::exists($fileFolder) &&  File::isDirectory($fileFolder)) {
                File::deleteDirectory($fileFolder);
            }

            $file->delete();
        }

        // return $this->successResponse('file deleted');
        // file pond requests for empty string on successful deletion
        return response("", 200);
    }

    public function download(Request $request)
    {
        $file_path = $request->file_path;

        if (! Storage::disk('public')->exists($file_path)) {
            return $this->notFoundResponse('File does not exist in storage');
        }

        return Storage::disk('public')->download($file_path);
    }
}
