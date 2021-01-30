<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Cost;
use App\Kargo;
use App\Product;
use App\Traits\ImageTrait;
use App\Traits\KargoTrait;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Container\RewindableGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;


class AdminController extends Controller

{
    use KargoTrait, ImageTrait;

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
     * confirm the given kargo
     * only super privilege users can confirm kargos
     * @param Request $request
     * @param Kargo $kargo
     * @return string
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
        if ($request->has('image')) {
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
        } else {
            return 'it should have image';
        }
    }

    /**
     * update the kargo record for the given user
     * super privilege users are able to update both confirmed and not confirmed kargos
     * @param Request $request
     * @param User $user
     * @param Kargo $kargo
     * @throws AuthorizationException
     */
    public function updateKargo(Request $request, User $user, Kargo $kargo)
    {

        $this->authorize('updateKargo', Admin::class);
        $request->validate([
            'receiver_name' => 'required',
            'receiver_tel' => 'required',
            'receiver_address' => 'required',
            'sending_date' => 'required'
        ]);
        $kargoData = [
            'receiver_name' => $request->input('receiver_name'),
            'receiver_tel' => $request->input('receiver_tel'),
            'receiver_address' => $request->input('receiver_address'),
            'sending_date' => $request->input('sending_date')
        ];
        $user->kargos()->update($kargoData);
    }

    /**
     * delete the kargo for the given user
     * super privilege users are able to delete both confirmed and not confirmed kargos
     * @param User $user
     * @param Kargo $kargo
     * @throws AuthorizationException
     */
    public function deleteKargo(User $user, Kargo $kargo)
    {
        $this->authorize('deleteKargo', Admin::class);
        $imageNameArray = $kargo->images()->where('imagable_id', $kargo->id)->pluck('image_name');
        DB::transaction(function () use($kargo, $imageNameArray) {
            //delete the kargo's image file from directory
            $this->deleteOne('public', $imageNameArray);
            //delete the kargo image records
            $kargo->images()->delete();
            //delete the given kargo record
            $kargo->delete();
        }, 1);
    }

    /**
     * add items to the kargo
     * super privilege users are able to add items to the kargo
     * @param User $user
     * @param Kargo $kargo
     * @param Product $product
     * @return string
     * @throws AuthorizationException
     */
    public function addToKargo(User $user, Kargo $kargo, Product $product)
    {
        $this->authorize('updateKargo', Admin::class);
        if($product->user()->value('id') != $user->id ) {
            return Redirect::back()->withErrors('msg', trans('translate.wrong_kargo_add'));
        } else {
            $kargo->products()->save($product);
            $kargo->refresh();
        }
    }

    public function removeFromKargo(Kargo $kargo, Product $product) {
        $this->authorize('updateKargo', Admin::class);
        $kargo->products()->delete($product);
        $kargo->refresh();
    }
}
