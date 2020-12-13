<?php

namespace App\Http\Controllers;

use App\Image;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;

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
     */
    public function store(Request $request)
    {

        $this->authorize('create', Image::class );
        $user = Auth::user();
        $request->validate([
            'imagable_type' => 'required',
            'imagable_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        if($request->has('image')) {
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
        }
    }

    /**
     * show a single Image
     */
    public function show(Image $image)
    {
        $this->authorize('view', $image);
        return Auth::user()->images->find($image);
    }
}
