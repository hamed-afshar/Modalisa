<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\KargoResource;
use App\Kargo;
use App\Product;
use App\Traits\ImageTrait;
use App\Traits\KargoTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KargoController extends Controller
{
    use KargoTrait, ImageTrait;

    /**
     * users should have see-kargos permission to be allowed
     * users can only see their own records
     * index kargos with all related products
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Kargo::class);
        $kargos = Auth::user()->kargos()->with(['products'])->get();
        return response(['kargos' => KargoResource::collection($kargos), 'message' => trans('translate.retrieved')], 200);
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
        return response(['message' => trans('translate.kargo_created')], 200);
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
        $kargo = $kargo->with('products')->where('id' , '=', $kargo->id)->get();
        return response(['kargo' => new KargoResource($kargo), 'message' => trans('translate.retrieved')], 200);
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
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function update(Request $request, Kargo $kargo)
    {
        $this->authorize('update', $kargo);
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
        $kargo->update($kargoData);
        return response(['kargo' => new KargoResource($kargo), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * delete a kargo
     * users with delete-kargos are allowed
     * users can delete their own records
     * no need to delete related image records because this record has not confirmed yet
     * @param Kargo $kargo
     * @return Application|ResponseFactory|Response
     * @throws Exception
     * @throws AuthorizationException
     */
    public function destroy(Kargo $kargo)
    {
        $this->authorize('delete', $kargo);
        $kargo->delete();
        return response(['message' => trans('translate.deleted')], 200);
    }

    /**
     * add products to the given kargo
     * users should have create-kargos permission to be allowed
     * users can only add items to their own records
     * users can not add items to confirmed kargos
     * @param Kargo $kargo
     * @param Product $product
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function addTo(Kargo $kargo, Product $product)
    {
        $this->authorize('update', $kargo);
        $kargo->products()->save($product);
        return response(['kargo' => new KargoResource($kargo->with('products')->where('id', '=', $kargo->id)->get()), 'message' => trans('translate.added_to_kargo')], 200);
    }

    /**
     * remove products from the given kargo
     * users should have create-kargos permission to be allowed
     * users can only remove items from their own records
     * users can not remove items from confirmed kargos
     * @param Kargo $kargo
     * @param Product $product
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function removeFrom(Kargo $kargo, Product $product)
    {
        $this->authorize('update', $kargo);
        $kargo->products()->where('id', '=', $product->id)->delete();
        return response(['kargo' => new KargoResource($kargo->with('products')->where('id', '=', $kargo->id)->get()), 'message' => trans('translate.remove_from_kargo')], 200);
    }
}
