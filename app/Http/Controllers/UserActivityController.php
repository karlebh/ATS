<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserActivityController extends Controller
{
    public function handleUserLeftForm()
    {
        if (Auth::check()) {
            $temporaryFiles = TemporaryFile::where('user_id', Auth::id())->get();

            foreach ($temporaryFiles as $file) {
                $fileFolder = storage_path("/app/public/tmp/" . $file->folder);

                if (File::exists($fileFolder) &&  File::isDirectory($fileFolder)) {
                    File::deleteDirectory($fileFolder);
                }

                $file->delete();
            }
        }
    }
}
