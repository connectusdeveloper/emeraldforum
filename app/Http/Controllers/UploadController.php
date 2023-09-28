<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __invoke(Request $request)
    {
        if (demo()) {
            return response(['message' => __('This feature is disabled on demo.')], 422);
        }

        $settings = site_config();
        if (1 != ($settings['allowed_upload'] ?? 0)) {
            return response(['message' => __('Uploads are disabled on the this site.')], 422);
        }

        if (auth()->user()->cant('uploads')) {
            return response(['message' => __('You do not have permissions to perform this action.')], 422);
        }

        $request->validate(
            ['file' => 'required|max:' . ($settings['upload_size'] ?? '2048') . '|mimes:' . ($settings['allowed_files'] ?? 'jpg,jpeg,png,pdf')],
            ['file.mimes' => __('The file type is not allowed.')]
        );

        $path = null;
        $extension = $request->file->extension();
        $name = $request->file->getClientOriginalName();
        $disk = env('ATTACHMENT_DISK', 'site');
        if ('site' == $disk) {
            $path = $request->file->store('uploads/' . auth()->id(), 'site');
        } else {
            $path = $request->file->storePublicly('uploads/' . auth()->id(), 's3');
        }

        return response([
            'extension' => $extension,
            'filename'  => Storage::disk($disk)->url($path),
            'name'      => str($name)->startsWith('image') ? $request->file->hashName() : $name,
        ]);
    }
}
