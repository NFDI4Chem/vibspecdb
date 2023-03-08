<?php

namespace App\Actions\FileSystem;

use App\Models\FileSystemObject;
use App\Models\Project;
use App\Models\Study;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

// use Illuminate\Session\TokenMismatchException;
// use Symfony\Component\HttpKernel\Exception\HttpException;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class CreateFileObject
{
    /**
     * Create a study.
     *
     * @param  array  $input
     * @return \App\Models\FileSystemObject
     */
    public function create(array $input, string $type = 'file')
    {

        $messages = [
            'name' => 'The :attribute required must be string.',
            'type' => 'The :attribute required must be string.',
            'project_id' => 'The :attribute required must be integer.',
            'study_id' => 'The :attribute required must be integer.',
            'parent_id' => 'The :attribute required must be integer.',
            'level' => 'The :attribute required must be integer.',
        ];

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'project_id' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'study_id' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'parent_id' => ['required', 'numeric', 'min:0'],
            'level' => ['required', 'numeric', 'min:0'],
        ])->validate();

        try {

            return DB::transaction(function () use ($input, $type) {

                // extract name;
                $name = $input["type"] == 'directory' ? ($input["name"] ?? "NewFolder") : $input["name"];

                // extract path:
                $project = Project::findOrFail($input['project_id'] ?? -1);
                $study = Study::findOrFail($input['study_id'] ?? -1);
                // $environment = env('APP_ENV', 'local');
                $baseDataFolder = "UserData";
                $path_regular = $input["type"] == 'file' ? 
                    str_replace('//', '/', implode('/', [
                        $baseDataFolder, 
                        ($project->uuid ?? 'common'),
                        ($study->uuid ?? 'common'),
                        $name
                    ])) : '/' . $name;

                if ($type === 'zip') {
                    $path_zip = str_replace('//', '/', $input['path']);
                }

                return tap(FileSystemObject::create([
                    "has_children" => FALSE,
                    "color" => "#fff",
                    "\$droppable" => TRUE,
                    "\$draggable" => TRUE,
                    'is_processed' => TRUE,
                    'is_archived' => $input["is_archived"] ?? FALSE,
                    "compressionInfo" => NULL,
                    "owner_id" => auth()->user()->id ?? 0,
                    "children" => [],
                    "key" =>  $name,
                    "description" => $name,
                    "slug" => strtolower($name),
                    "parent_id" => $input["parent_id"] ?? 0,
                    "name" => $name,
                    "project_id" => $input["project_id"] ?? -1,
                    "study_id" => $input["study_id"] ?? -1,
                    "type" => $input["type"] ?? 'directory',
                    "level" => (int)$input["level"],
                    "ftype" => $input["ftype"] ?? '',
                    "size" => (int)($input["size"] ?? 0),
                    "uppyid" => $input["uppyid"] ?? '',
                    "path" => $type === 'zip' ? $path_zip : $path_regular,
                    "relative_url" => $input["relative_url"] ?? '',
                ]), function (FileSystemObject $file) {
                    FileSystemObject::where('id', $file->parent_id ?? 0)
                        ->update(['has_children' => TRUE]);
                });
            });

        } catch (ModelNotFoundException $e) {
            throw ValidationException::withMessages([
                'not_exists' => $e->getMessage(),
            ]);
        } 
        catch(Throwable $e)
        {
            throw ValidationException::withMessages([
                'exception' => $e->getMessage(),
            ]); 
        }
    }
}
