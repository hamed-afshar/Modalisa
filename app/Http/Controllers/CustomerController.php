<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * index customers
     * users should have see-customers permission to be allowed
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Customer::class);
        return Auth::user()->customers;
    }

    /**
     * form to create customer
     * VueJs modal generates this form
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
    }

    /**
     * store customers
     * users should have create-customers permission to be allowed
     * @param Request $request
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Customer::class);
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'tel' => 'required',
            'communication_media' => 'required',
            'communication_id' => 'required',
        ]);
        $customerData = [
            'name' => $request->input('name'),
            'tel' => $request->input('tel'),
            'communication_media' => $request->input('communication_media'),
            'communication_id' => $request->input('communication_id'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
        ];
        $user->customers()->create($customerData);
    }

    /**
     * show a single customer
     * users should have see-customers permission to be allowed
     * VueJs shows this customer
     * @param Customer $customer
     * @return Customer
     * @throws AuthorizationException
     */
    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        return Auth::user()->customers->find($customer);
    }

    /**
     * edit form
     * VueJs generates this form
     * @param Customer $customer
     * @throws AuthorizationException
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
    }

    /**
     * update customers
     * users should have create-customers permission to be allowed
     * users can only update their own records
     * @param Request $request
     * @param Customer $customer
     * @throws AuthorizationException
     */
    public function update(Request $request, Customer $customer)
    {
        $this->authorize('update', $customer);
        request()->validate([
            'name' => 'required',
            'tel' => 'required',
            'communication_media' => 'required',
            'communication_id' => 'required',
        ]);
        $data = [
            'name' => $request->input('name'),
            'tel' => $request->input('tel'),
            'communication_media' => $request->input('communication_media'),
            'communication_id' => $request->input('communication_id'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
        ];
        $customer->update($data);
    }

    /**
     * delete customers
     * users should have delete-customers permission to be allowed
     * users can o nly delete their own records
     * @param Customer $customer
     * @throws AuthorizationException
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $customer->delete();
    }

}
