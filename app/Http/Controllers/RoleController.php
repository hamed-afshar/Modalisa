<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //store role instance in db
    public function store()
    {
        $data = request([
           'name' => 'name'
        ]);
        Role::create($data);
    }
}
