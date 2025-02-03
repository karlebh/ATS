<?php

namespace App\Actions\PurchaseOrder;

use App\Traits\ResponseTrait;
use Illuminate\Support\Str;

class UploadAction
{
    use ResponseTrait;

    public function execute(array $files)
    {
        if (empty($files)) {
            $this->badRequestResponse('No files were uploaded.', code: 422);
        }

        $paths = [];

        foreach ($files['file'] as $file) {
            $fileName = now()->timestamp . '-' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $paths[] = $file->storeAs('tmp', $fileName, 'public');
        }

        return $this->successResponse('File(s) uploaded succesfully', ['files' => $paths]);
    }
}
