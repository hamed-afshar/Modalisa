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
        $user = $request->input('user');
        // prepare cost data
        $request->validate([
            'user' => 'required',
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
        // create a cost record for the given user
        $cost = $user->costs()->create($costData);
        // if image is included on cost creation time, then uploading image and model will be created in db
        if($request->has('image')) {
            $oldImageName = $cost->images()->where('imagable_id', $cost->id)->value('image_name');
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images/';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            $this->deleteOne('public', $oldImageName);
            $imageData = [
                // imagable_type always remains App\Cost
                'imagable_type' => 'App\Cost',
                'imagable_id' => $cost->id,
                'image_name' => $filePath
            ];
            // create a image record for the given user
            $user->images()->create($imageData);
        }
    }

    /** show a single cost
     * users with see-costs permission only can see cost records belongs to them
     * @param Cost $cost
     * @return Cost
     * @throws AuthorizationException
     */
    public function show(Cost $cost)
    {
        $this->authorize('view', $cost);
        return Auth::user()->costs->find($cost);
    }

    /**
     * edit form
     * VueJs generates this form
     * only BuyerAdmin or other admin users with create-costs permission will be able to update costs
     * @param Cost $cost
     * @throws AuthorizationException
     */
    public function edit(Cost $cost)
    {
        $this->authorize('update', $cost);
    }

    /**
     * update a cost record
     * only BuyerAdmin or other admin users with create-costs permission will be able to update costs
     * @param Request $request
     * @param Cost $cost
     * @throws AuthorizationException
     */
    public function update(Request $request, Cost $cost)
    {
        $this->authorize('update', $cost);
        $request->validate([
           'user' => 'required',
           'amount' => 'required',
           'description' => 'required',
        ]);
        $user = $request->input('user');
        $costData = [
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
        ];
        //update the cost record
        $cost->update($costData);
        // if request has image for update then new image name will be generated and old image be deleted
        // if request does not have image, then image will not change
        if($request->has('image')) {
            $oldImageName = $cost->images()->where('imagable_id', $cost->id)->get('image_name');
            $image = $request->file('image');
            $imageNewName = date('mdYHis') . uniqid();
            $folder = '/images';
            $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $imageNewName);
            $this->deleteOne('public', $oldImageName);
            $imageData = [
                // imagable_type always remains App\\Cost
                'imagable_type' => 'App\Cost',
                'imagable_id' => $cost->id,
                'image_name' => $filePath
            ];
            // update image record for the given user
            $user->images()->update($imageData);
        }
    }


}
