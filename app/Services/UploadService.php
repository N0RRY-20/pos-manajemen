<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    public function upload(
        UploadedFile $file,
        string $folder = 'default',
        ?string $filename = null
    ): string {
        // Tambahkan timestamp unik di belakang nama file untuk menghindari overwrite
        $filename = $filename ? Str::slug($filename).'-'.time() : uniqid();

        $extension = $file->getClientOriginalExtension();

        $fullname = (string) $filename.'.'.$extension;

        return $file->storeAs($folder, $fullname, 'public');
    }

    public function delete(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
