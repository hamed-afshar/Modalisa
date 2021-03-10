<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        //return user with subscriptions and roles
        $users = User::with(['subscription', 'role'])->get();
        return response(['users' => UserResource::collection($users), 'message' => trans('translate.retrieved')], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     * @throws AuthorizationException
     */
    public function show(User $user)
    {
        $this->authorize('view', User::class);
        return response(['users' => UserResource::collection($user), 'message' => trans('translate.retrieved')], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $data = request()->all();
        $user->update($data);
        return response(['users' => UserResource::collection($user), 'message' => trans('translate.retrieved')], 200);

    }

    /**
     * users can update their profile
     * users can not update other user's profile
     * @param User $user
     * @throws AuthorizationException
     */
    public function editProfile(User $user)
    {
        $this->authorize('profile', $user);
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'language' => 'required',
            'tel' => 'required',
            'country' => 'required',
            'communication_media' => 'required'
        ]);
        $user->update($data);
        return response(['users' => UserResource::collection($user), 'message' => trans('translate.retrieved')], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        return response(['message' => trans('translate.deleted')]);
    }
}
