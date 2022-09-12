<?php

namespace App\Http\Controllers;

use App\Models\FileSystemObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Actions\FileSystem\CreateNewObject;
use App\Actions\FileSystem\UpdateFileObject;

class FileSystemController extends Controller
{
    public function create(Request $request, CreateNewObject $creator) {
        $fileObject = $creator->create($request->all());
        return $request->wantsJson() ? new JsonResponse($fileObject, 200) : back()->with('status', 'object-created');
    }

    public function destroy(Request $request, FileSystemObject $file)
    {
        $file->delete();
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'file-system-object-deleted');
    }

    public function update(Request $request, UpdateFileObject $updater, FileSystemObject $file)
    {
        $updater->update($file, $request->all());
        return $request->wantsJson() ? new JsonResponse('', 200) : back()->with('status', 'file-updated');
    }
}
