<?php

namespace App\Http\Controllers;

use App\Cost;
use App\Traits\ImageTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostController extends Controller
{
    use ImageTrait;

    /**
     * index costs by retailers
     * to index, retailers must have see-costs permission
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Cost::class);
        return Auth::user()->costs;
    }

    /**
     * index all costs for a specific model
     * to index, retailers must have see-costs permission
     * @param $model
     * @return
     * @throws AuthorizationException
     */
    public function indexModel($model)
    {
        $this->authorize('viewAny', Cost::class);
        return Auth::user()->costs()->where('costable_type', $model)->get();
    }

    /**
     * form to create cost
     * VueJs modal generates this form
     * only BuyerAdmin or maybe other admins with create-costs permission can store cost in db
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Cost::class);
    }

    /**
     * store costs
     * only BuyerAdmin or maybe other admins with create-costs permission can store cost in db
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        // first cost must be created and get cost_id to be used in image creation model
        $this->authorize('create', Cost::class);
        $user = Auth::user();
        // prepare cost data
        $request->validate([
            'amount' => 'required',
            'description' => 'required',
            'costable_type' => 'required',
            'costable_id' => 'required'
        ]);
        $costData = [
          'amount' => $request->input('amount'),
          'description' => $request->input('description'),
          'costable_type' => $request->input('costable_type'),
          'costable_id' => $request->input('costable_id')
        ];
        // create a cost
        $cost = $user->costs()->create($costData);
        // if image is included on cost creation time, then uploading image and model will be created in db
        if($request->has('image')) {
            $image = $request->file('image');
            $imageName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageName);
            $imageData = [
                // imagable_type always remains App\Cost
                'imagable_type' => 'App\Cost',
                'imagable_id' => $cost->id,
                'image_name' => $filePath
            ];
            $user->images()->create($imageData);
        }
    }
}
