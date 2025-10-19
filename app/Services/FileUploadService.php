<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Store a file and return its path.
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $disk
     * @return string|false
     */
    public function store(UploadedFile $file, string $path, string $disk = 'public')
    {
        return $file->store($path, $disk);
    }

    /**
     * Delete a file.
     *
     * @param string|null $path
     * @param string $disk
     * @return bool
     */
    public function delete(?string $path, string $disk = 'public'): bool
    {
        if (!$path) {
            return false;
        }

        return Storage::disk($disk)->delete($path);
    }
}
