<?php

namespace App\Services;

// use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\FileSystemObject;

class ParseMetadata
{

    public function __construct()
    {
    }

    public function updateParseParent(FileSystemObject $file) {
      try {
        if ($fileParent = FileSystemObject::find($file['parent_id'])) {
          if ($fileParent->type != 'dataset') { 
            return redirect()->back()->withErrors([
                'update' => 'Parent is not dataset type'
            ]);
           }

           $this->updateFilesMeta($fileParent);
        }

        } catch (Throwable $exception) {
          return redirect()->back()->withErrors([
              'update' => 'Failed to update meta data'
          ]);
      }
    }

    public function updateFilesMeta(FileSystemObject $file) {

      try {
          $ramanService = new RamanService();
          $list = [];
          $this->cascadeExtract($file->children, $list);

          $data = $ramanService->getSpectra(['files' => $list]);
          $meta = $data['meta'] ?? [];
          $info = $data['info'] ?? [];

          $missing_metadata = $data['warnings']['metadata_missing'] ?? false;

          $updated_meta = $this->extractMetadata($meta);
          $this->extractFilesId($list, $updated_meta);


          collect($updated_meta)->map(function ($item) use ($missing_metadata) {
              if ($file = FileSystemObject::find($item['id'])) {
                  unset($item['id']);
                  if ($missing_metadata) { $item = []; }
                  if (!$file->syncMeta($item)) {
                      return back()->withErrors(
                          ["metadata" => "Can not update metadata for the file."]
                      );
                  }
              }
          });

          return back()->withSuccess('files meta updated');

      } catch (Throwable $exception) {
          return redirect()->back()->withErrors([
              'create' => 'Failed to update meta data'
          ]);
      }
  }

  private function getFileId($list, $path) {
      foreach ($list as $list_item) {
          if ($list_item['path'] == implode('/', ['',$path])) {
              return $list_item['id'];
          }
      }
      return -1;
  }

  private function extractFilesId($files, &$list) {
      foreach ($list as $key=>$list_item) {
          $list[$key]['id'] =  $this->getFileId($files, $list_item['filename']);
      }
  }

  private function extractMetadata($meta) {
      $updated_meta = [];
      foreach (array_keys($meta) as $meta_key=>$meta_item) {
          foreach ($meta[$meta_item] as $file_id=>$item) {
              $updated_meta[$file_id][$meta_item] = $item;
          }
      }
      return $updated_meta;
  }

  private function cascadeExtract($childrens, &$fileList) {
      foreach ($childrens as $child) {
          if (!in_array($child->type, ['directory', 'dataset'])) {
              if (
                  ($child->type == 'metafile' && FileSystemObject::isMetadataFile($child)) || 
                  !FileSystemObject::isMetadataFile($child)
              ) {
                  $fileList[] = [
                      'id' => $child->id,
                      'name' => $child->name,
                      'path' => $child->relative_url,
                      'src' => $child->path,
                  ];
              }
          }
          $this->cascadeExtract($child->children, $fileList);
      }
  }


}