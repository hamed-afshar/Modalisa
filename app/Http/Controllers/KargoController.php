<?php

namespace App\Http\Controllers;

use App\Kargo;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KargoController extends Controller
{
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
        $user = Auth::user();
        $request->validate([
            'receiver_name' => 'required',
            'receiver_tel' => 'required',
            'receiver_address' => 'required',
            'sending_date' => 'required | date_format:Y-m-d'
        ]);
        $kargoData = [
            'receiver_name' => $request->input('receiver_name'),
            'receiver_tel' => $request->input('receiver_tel'),
            'receiver_address' => $request->input('receiver_address'),
            'sending_date' => $request->input('sending_date')
        ];
        $user->kargos()->create($kargoData);
    }

}
