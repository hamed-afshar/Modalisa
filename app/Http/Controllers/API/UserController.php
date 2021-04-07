<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
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
        return response(['users' => new UserResource($user), 'message' => trans('translate.retrieved')], 200);
    }

    /**
     * lock a user
     * @param Request $request
     * @param User $user
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function lock(Request $request,User $user)
    {
        $this->authorize('update', User::Class);
        $data = [
          'locked' => (int)$request->input('locked'),
        ];
        $user->update($data);
        return response(['users' => new UserResource($user), 'message' => trans('translate.user_updated')]);
    }

    /**
     * confirm a user
     * @param Request $request
     * @param User $user
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function confirm(Request $request,User $user)
    {
        $this->authorize('update', User::Class);
        $data = [
            'confirmed' => (int)$request->input('confirmed'),
        ];
        $user->update($data);
        return response(['users' => new UserResource($user), 'message' => trans('translate.user_updated')]);
    }

    /**
     * users can update their profile
     * users can not update other user's profile
     * @param User $user
     * @return Application|ResponseFactory|Response
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
        return response(['users' => new UserResource($user), 'message' => trans('translate.retrieved')], 200);

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
    }
}
