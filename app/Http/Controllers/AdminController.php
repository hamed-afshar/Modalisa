<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Cost;
use App\Kargo;
use App\Traits\KargoTrait;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller

{
    use KargoTrait;

    /**
     * index costs for the given user
     * super privilege users are able to see all costs created for any retailer
     * @param User $user
     * @return mixed
     * @throws AuthorizationException
     */
    public function indexCosts(User $user)
    {
        $this->authorize('indexCosts', Admin::class);
        return $user->costs;
    }

    /**
     * show a single cost for the given user
     * super privilege users are able to see a single cost for a specific user
     * @param User $user
     * @param Cost $cost
     * @return mixed
     * @throws AuthorizationException
     */
    public function showCost(User $user, Cost $cost)
    {
        $this->authorize('indexSingleCost', Admin::class);
        return $user->costs;
    }

    /**
     * create kargo for the given user
     * super privilege users are able to create kargo for the given user
     * @param Request $request
     * @param User $user
     * @throws AuthorizationException
     */

    public function storeKargo(Request $request, User $user)
    {
        $this->authorize('createKargo', Admin::class);
        $request->validate([
            'receiver_name' => 'required',
            'receiver_tel' => 'required',
            'receiver_address' => 'required',
            'sending_date' => 'required | date_format:Y-m-d',
            'kargo_list' => 'required'
        ]);
        $kargoData = [
            'receiver_name' => $request->input('receiver_name'),
            'receiver_tel' => $request->input('receiver_tel'),
            'receiver_address' => $request->input('receiver_address'),
            'sending_date' => $request->input('sending_date')
        ];
        $kargoList = $request->input('kargo_list');
        $this->createKargo($user, $kargoData, $kargoList);
    }


    /**
     * index all kargos
     * super privilege users are able to see all kargos with related user
     * @throws AuthorizationException
     */
    public function indexKargos()
    {
        $this->authorize('indexKargos', Admin::class);
        return Kargo::with(['user'])->get();
    }

    /**
     * show a single kargo
     * super privilege users are able to see all kargos with all related user and products
     * @throws AuthorizationException
     */
    public function showKargo()
    {
        $this->authorize('indexSingleKargo', Admin::class);
        return Kargo::with(['user', 'products'])->get();
    }

    /**
     * confirm the kargo
     * only super privilege users can confirm kargos
     * @param Request $request
     * @param Kargo $kargo
     * @throws AuthorizationException
     */
    public function confirm(Request $request, Kargo $kargo)
    {
        $this->authorize('confirm', Admin::class);
        // kargo will be confirmed for this user
        $user = $kargo->user;
        // first upload the kargo, then upload the image
        $request->validate([
            'weight' => 'required',
            'confirmed' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
        $data = [
            'weight' => $request->input('weight'),
            'confirmed' => $request->input('confirmed'),
        ];
        $kargo->update($data);
        // upload image for the kargo
        $image = $request->file('image');
        $imageNewName = date('mdYHis') . uniqid();
        $folder = '/images/';
        $filePath = $folder . $imageNewName . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $imageNewName);
        // create record for the uploaded image
        $imageData = [
            // imagable_type always remains App\Kargo
            'imagable_type' => 'App\Kargo',
            'imagable_id' => $kargo->id,
            'image_name' => $filePath
        ];
        $user->images()->create($imageData);
    }
}
