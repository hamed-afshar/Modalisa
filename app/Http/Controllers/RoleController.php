<?php

namespace App\Http\Controllers;

use App\AccessProvider;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //store role instance in db
    public function store()
    {
       // $accessProvider = new AccessProvider(auth()->user()->id, 'create-role');
        dd(DB::table('roles')->where('id', 1)->first()->name);

        Role::create(request([
            'name' => 'name'
        ]));
    }

}