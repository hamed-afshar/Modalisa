<?php

namespace App\Http\Controllers;

use App\Kargo;
use App\Product;
use App\Traits\ImageTrait;
use App\Traits\KargoTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KargoController extends Controller
{
    use KargoTrait, ImageTrait;

    /**
     * users should have see-kargos permission to be allowed
     * users can only see their own records
     * index kargos with all related products
     */
    public function index()
    {
        $this->authorize('viewAny', Kargo::class);
        return Auth::user()->kargos()->with(['products'])->get();
    }

    /**
     * form to create kargo
     * VueJs modal generates this form
     * Only users with create-Kargos permission are allowed
     */
    public function create()
    {
        $this->authorize('create', Kargo::class);
    }

    /**
     * store kargos
     * only users with create-kargos permission are allowed
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Kargo::class);
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
        $user = Auth::user();
        $kargoList = $request->input('kargo_list');
        $this->createKargo($user, $kargoData, $kargoList);
    }

    /**
     * show a single kargo
     * users with see-kargo permission are allowed
     * users can only see their own records
     * @param Kargo $kargo
     * @return mixed
     * @throws AuthorizationException
     */
    public function show(Kargo $kargo)
    {
        $this->authorize('view', $kargo);
        $user = Auth::user();
        return $user->kargos->find($kargo)->with('products')->get();
    }

    /**
     * edit form
     * VueJs generates this form
     * @param Kargo $kargo
     * @throws AuthorizationException
     */
    public function edit(Kargo $kargo)
    {
        $this->authorize('update', $kargo);
    }


    /**
     * update a kargo
     * users with create-kargos permission are allowed
     * users can update their own records
     * @param Request $request
     * @param Kargo $kargo
     * @throws AuthorizationException
     */
    public function update(Request $request, Kargo $kargo)
    {
        $this->authorize('update', $kargo);
        $user= Auth::user();
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
     * delete a kargo
     * users with delete-kargos are allowed
     * users can delete their own records
     * no need to delete related image records because this record has not confirmed yet
     * @param Kargo $kargo
     * @throws Exception
     */
    public function destroy(Kargo $kargo)
    {
        $this->authorize('delete', $kargo);
        $kargo->delete();
    }

    /**
     * add products to the given kargo
     * users should have create-kargos permission to be allowed
     * users can only add items to their own records
     * @param Kargo $kargo
     * @param Product $product
     * @throws AuthorizationException
     */
    public function addTo(Kargo $kargo, Product $product)
    {
        $this->authorize('update', $kargo);
        $kargo->products()->save($product);
    }

    /**
     * remove products from the given kargo
     * users should have create-kargos permission to be allowed
     * users can only remove items from their own records
     * @param Kargo $kargo
     * @param Product $product
     * @throws AuthorizationException
     */
    public function removeFrom(Kargo $kargo, Product $product)
    {
        $this->authorize('update', $kargo);
        $kargo->products()->delete($product);
    }



}
