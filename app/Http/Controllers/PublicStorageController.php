<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicStorageController extends Controller
{
    /**
     * Serve files stored on the "public" disk when a webserver storage symlink is missing.
     */
    public function __invoke(string $path): BinaryFileResponse
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $allowedPrefixes = [
            'logos/',
            'employee-avatars/',
            'seed-avatars/',
            'seed-icons/',
        ];

        $isAllowed = false;
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            abort(404);
        }

        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return response()->file($disk->path($path));
        }

        // Some shared hosts can't create the /public/storage symlink, and instead
        // assets may be manually copied into /public/storage.
        $legacyPath = public_path('storage/' . $path);
        if (is_file($legacyPath)) {
            return response()->file($legacyPath);
        }

        abort(404);
    }
}
