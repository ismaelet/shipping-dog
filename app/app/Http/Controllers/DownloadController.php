<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download($filename)
    {
        // Check if the file exists
        if (Storage::exists('public/'.$filename)) {
            // Download the file
            return FileHelper::download($filename);
        } else {
            // File not found
            return abort(404);
        }
    }
}
