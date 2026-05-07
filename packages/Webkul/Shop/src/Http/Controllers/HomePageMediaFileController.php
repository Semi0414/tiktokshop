<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HomePageMediaFileController extends Controller
{
    /**
     * Serve a file from storage/app/public/home-page-media (no symlink to public/storage required).
     */
    public function show(string $file): StreamedResponse
    {
        $file = basename($file);
        if ($file === '' || $file === '.' || $file === '..') {
            abort(404);
        }
        if (! preg_match('/^[A-Za-z0-9._\-]+$/', $file)) {
            abort(404);
        }

        $path = 'home-page-media/'.$file;

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path, $file, [], 'inline');
    }
}
