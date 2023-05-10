<?php
  
namespace App\Http\Controllers;
  

use App\Models\Image;

use App\Actions\Image\CreateImage;
use App\Actions\Image\UpdateImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

  
class ImageUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('imageUpload');
    }
      
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateImage $creator, UpdateImage $updater)
    {

      try {

        // define input validation
        $request->validate([
          'image.file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
          'type' => 'required',
          'itemId' => 'required',
        ]);

        // define general params
        $params = $request->all();

        $image = $request->image['file'];

        // define input file props
        $type = $image->extension();
        $size = $image->getSize();
        $storage_path = implode('/', ['CoverImages', $params['type'], $params['itemId'] ]);
        $name = implode('.', [$params['type'], $params['itemId'], 'head', 'photo', $type]);
        $path = Storage::disk(env('FILESYSTEM_DRIVER', 'minio'))->putFileAs($storage_path, $image, $name);


        // define to which object file belongs to
        $item_key = '';
        switch ($params['type']) {
          case 'study':
            $item_key = 'study_id';
              break;
          case 'project':
            $item_key = 'project_id';
              break;
          case 'user':
            $item_key = 'user_id';
              break;
          default:
            return back()
            ->with('error','Can not store the image')
            ->with('image', $name); 
        }

        // build image file object to save into DB
        $image2save = [
          'name' => $name,
          'path' => $path,
          'type' => $type,
          'size' => $size,
          'owner_id' => auth()->user()->id,
        ];
        $image2save[$item_key] = $params['itemId'];

        // save or create object into DB
        $imageDB = Image::where($item_key, $params['itemId'])->get()->first();
        if ($imageDB) {
          $updater->update($imageDB, $image2save);
        } else {
          $creator->create($image2save);
        }
        
        return back()
            ->with('success','You have successfully upload image.')
            ->with('image', $name);
      } catch (Throwable $e) {
        return redirect()->back()->withErrors([
          'upload' => 'Failed to upload file.'
        ]);
      }
    }
}