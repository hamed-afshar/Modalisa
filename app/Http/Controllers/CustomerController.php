<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * index customers
     */
    public function index()
    {
        $this->authorize('viewAny', Customer::class);
        return Auth::user()->customers;
    }

    /**
     * form to create customer
     * VueJs modal generates this form
     */
    public function create()
    {
        $this->authorize('create', Customer::class);
    }

    /**
     * store customers
     */
    public function store()
    {
        $this->authorize('create', Customer::class);
        $user = Auth::user();
        $data = request()->validate([
            'name' => 'required',
            'tel' => 'required',
            'communication_media' => 'required',
            'communication_id' => 'required',
        ]);
        $user->customers()->create($data);
    }

    /**
     * show a single customer
     * VueJs shows this customer
     */
    public function show(Customer $customer)
    {
        $this->authorize('view', $customer);
        return Auth::user()->customers->find($customer);
    }

    /**
     * edit form
     * VueJs generates this form
     */
    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);
    }

    /**
     * update customers
     */
    public function update(Customer $customer)
    {
        $this->authorize('update', $customer);
        $data = request()->validate([
            'name' => 'required',
            'tel' => 'required',
            'communication_media' => 'required',
            'communication_id' => 'required',
        ]);
        $customer->update($data);
    }

    /**
     * delete customers
     */
    public function destroy(Customer $customer)
    {
        $this->authorize('delete', $customer);
        $customer->delete();
    }

}
