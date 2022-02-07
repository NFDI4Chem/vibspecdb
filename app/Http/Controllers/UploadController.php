<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        Storage::disk('minio')->put('example.txt', 'Contents');
        // Storage::disk('minio')->put('hello.json', '{"hello": "world"}', 'public');
        return [];
    }
}
