<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Image;
use App\Traits\ImageTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    use ImageTrait;

    /**
     * it is not necessary for a user to see all uploaded pictures
     */
    public function index()
    {
        $this->authorize('viewAny', Image::class);
    }

    /**
     * form to create a note
     * VueJs modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Image::class);
    }

    /**
     * store image
     * any user with create-images permission is allowed to upload images
     * @param Request $request
     * @return string
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Image::class);
        $user = Auth::user();
        $request->validate([
            'imagable_type' => 'required',
            'imagable_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $image = $request->file('image');
        $imageName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $imageName);
        $data = [
            'image_name' => $filePath,
            'imagable_type' => $request->input('imagable_type'),
            'imagable_id' => $request->input('imagable_id')
        ];
        $user->images()->create($data);
        return response(['message' => trans('translate.image_uploaded')], 200);
    }

    /**
     * show a single Image
     * users should have see-images permission to be allowed
     * @param Image $image
     * @return Image|Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function show(Image $image)
    {
        $this->authorize('view', $image);
        $image = Auth::user()->images->find($image);
        return response(['image' => new ImageResource($image)], 200);
    }

    /**
     * update photos
     * users should have create-images permission to be allowed
     * users can only update their own records
     * @param Request $request
     * @param Image $image
     * @return string
     * @throws AuthorizationException
     */
    public function update(Request $request, Image $image)
    {
        $this->authorize('update', $image);
        $request->validate([
            'imagable_type' => 'required',
            'imagable_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        // get current image name from db and change it with new name
        $oldImage = $image;
        $oldImageName = $oldImage->image_name;
        $image = $request->file('image');
        $newImageName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $newImageName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $newImageName);
        $this->deleteOne('public', [$oldImageName]);
        $data = [
            'imagable_type' => $request->input('imagable_type'),
            'imagable_id' => $request->input('imagable_id'),
            'image_name' => $filePath
        ];
        $oldImage->update($data);
        return response(['image' => new ImageResource($oldImage), 'message' => trans('translate.image_updated')], 200);
    }

    /**
     * delete images
     * users should have delete-images to be allowed
     * users can only delete their own images
     * @param Image $image
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function destroy(Image $image)
    {
        $this->authorize('delete', $image);
        $imageNameArray = [$image->image_name];
        $this->deleteOne('public', [$imageNameArray]);
        $image->delete();
        return response(['message' => trans('translate.image_deleted')], 200);
    }
}
