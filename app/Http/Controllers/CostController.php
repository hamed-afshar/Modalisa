<?php

namespace App\Http\Controllers;

use App\Cost;
use App\Traits\ImageTrait;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostController extends Controller
{
    use ImageTrait;

    /**
     * index costs created for retailers
     * users should have see-costs permission to be allowed
     * retailers can only see costs created for them
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Cost::class);
        //users can only index costs belongs to them
        return Auth::user()->costs;
    }

    /**
     * index all costs for the given object
     * to index, retailers must have see-costs permission
     * retailers can only see its own records
     * @param $id
     * @param $model
     * @return Cost
     * @throws AuthorizationException
     */
    public function indexModel($id, $model)
    {
        $this->authorize('viewAny', Cost::class);
        return Auth::user()->costs()->where(['costable_type' => $model, 'costable_id' => $id])->get();
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
     * store costs for the given user by BuyerAdmin
     * only BuyerAdmin or other admins with create-costs permission can store cost in db
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Cost::class);
    }

    /**
     * show a single cost
     * users with see-costs permission only can see cost records belong to them
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
    }

    /**
     * delete costs
     * only BuyerAdmin with delete-costs permission will be able to delete costs
     * image file and record also must be deleted accordingly.
     * @param Cost $cost
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(Cost $cost)
    {
        $this->authorize('delete', $cost);
    }
}
