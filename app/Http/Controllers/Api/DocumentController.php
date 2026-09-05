<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $dir = public_path('private_files');
        $documents = [];

        $isLocal = config('app.env') === 'local';

        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if (!in_array($file, ['.', '..'])) {
                    $path = 'private_files/' . rawurlencode($file);
                    $documents[] = $isLocal ? url($path) : secure_url($path);
                }
            }
        }

        return response()->json($documents);
    }
}
