<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FileHelper
{
    /**
     * Get the absolute filesystem path of a file in the public storage disk.
     */
    public static function storagePath(?string $path = null): string
    {
        return Storage::disk('public')->path(ltrim($path ?? '', '/'));
    }

    /**
     * Get the versioned URL of a file in the public storage disk.
     */
    public static function storageAsset(string $path): string
    {
        $path = ltrim($path, '/');

        if (! self::exists($path)) {
            throw new FileNotFoundException($path);
        }

        return asset('storage/'.$path).'?v='.self::lastModified($path);
    }

    /**
     * Check if a file exists in the public storage disk.
     */
    public static function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }

    /**
     * Get the last modified timestamp of a file in the public storage disk.
     */
    public static function lastModified(string $path): int
    {
        return Storage::disk('public')->lastModified($path);
    }

    /**
     * Delete a file from the public storage disk.
     */
    public static function delete(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    /**
     * Download a file from the public storage disk.
     */
    public static function download(string $path, ?string $name = null): BinaryFileResponse
    {
        if (! self::exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download(
            Storage::disk('public')->path($path),
            $name
        );
    }

    /**
     * Store an uploaded file at the given path in the public storage disk.
     */
    public static function upload(UploadedFile $file, string $path): bool
    {
        if (self::exists($path)) {
            self::delete($path);
        }

        $directory = dirname($path);
        $filename = basename($path);

        if ($directory === '.') {
            $directory = '';
        }

        return (bool) $file->storeAs($directory, $filename, 'public');
    }
}
