<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FileSystemObject;

class FileSystemObjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */


    public static $wrap = null;
    
    private bool $lite = true;

    private array $properties = [''];

    public function lite(bool $lite, array $properties): self
    {
        $this->lite = $lite;
        $this->properties = $properties;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'slug' => $this->slug,
            'owner_id' => $this->owner_id,
            'parent_id' => $this->parent_id,
            'project_id' => $this->project_id,
            'study_id' => $this->study_id,
            // 'is_root' => $this->is_root,
            // '$folded' => true,
            '$droppable' => in_array($this->type, ['directory', 'dataset']) ? true : false,
            '$draggable' => !$this->is_root,
            'has_children' => sizeof($this->children) > 0,
            'path' => $this->path,
            'relative_url' => $this->relative_url,
            // 'metadata' => $this->getMetadata(),
            $this->mergeWhen(! $this->lite, function () {
                // $this->limit = $this->limit + count($this->children);
                return [
                    $this->mergeWhen(
                        in_array('children', $this->properties),
                        function () {
                            $data = [
                                /*
                                'children' => FileSystemObject::with('children')
                                    ->where([['parent_id', $this->id]])
                                    ->orderBy('type')
                                    ->get(),
                                */
                                // 'limit' => $this->limit + count($this->children),
                                'level' => $this->level ? $this->level + 1 : 1,
                                // 'count' => sizeof($this->children),
                                'children' => ($this->children) ? collect($this->children)->map(function ($child) {
                                    return (new FileSystemObjectResource($child))->lite(false, ['children']);
                                }) : [],
                                // 'parent' => (new FileSystemObjectResource($this->parent)),
                            ];
                            return $data;
                        }
                    ),
                    $this->mergeWhen(
                        in_array('metadata', $this->properties),
                        function () {
                            return [
                                'metadata' => $this->getMetadata()
                            ];
                        }
                    ),
                ];
            }),
        ];
    }
}
