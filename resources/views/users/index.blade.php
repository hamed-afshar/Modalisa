@extends('dashboards._admin_header')
@section('index-content')
    <div class="mt-4">
        <div class="container mx-auto">
            <div class="grid grid-rows-1">
                <table class="table-auto mb-8">
                    <thead>
                    <tr class="bg-pink-600 text-white">
                        <th class="border px-4 py-2"> ID</th>
                        <th class="border px-4 py-2"> Name</th>
                        <th class="border px-4 py-2"> Email</th>
                        <th class="border px-4 py-2"> Subscription</th>
                        <th class="border px-4 py-2"> Confirmed</th>
                        <th class="border px-4 py-2"> Locked</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr class="bg-white">
                            <td class="border text-center px-4 py-2"> {{$user->id}} </td>
                            <td class="border px-4 py-2"> {{$user->name}} </td>
                            <td class="border px-4 py-2"> {{$user->email}} </td>
                            <td class="border px-4 py-2">
                                <select class="input-option w-full" id="subscription" name="subscription">
                                    <option selected disabled> {{$user->subscription->plan}} </option>
                                    <option value="Gold"> Gold </option>
                                    <option value="Silver"> Silver </option>
                                    <option value="Bronze"> Bronze </option>
                                </select>

                            </td>
                            <td class="border text-center px-4 py-2">
                                @if($user->confirmed == true)
                                    <i class="fas fa-check text-green-600"></i>
                                @else
                                    <i class="fas fa-ban text-red-600"></i>
                                @endif
                            </td>
                            <td class="border text-center px-4 py-2">
                                @if($user->locked == true)
                                    <i class="fas fa-ban text-red-600"></i>
                                @else
                                    <i class="fas fa-check text-green-600"></i>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <p> User's table is empty</p>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

