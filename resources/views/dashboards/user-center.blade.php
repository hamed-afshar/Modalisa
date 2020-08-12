@extends('dashboards._admin_header')
@section('index-content')
    <div id="app">
        <!-- user table -->
        <users-table>

        </users-table>
    </div>
{{--    <div class="grid grid-rows-1">--}}
{{--        <table class="table-auto mb-8">--}}
{{--            <thead>--}}
{{--            <tr class="table-header-row">--}}
{{--                <th class="table-header-cell"> {{ __('translate.id') }} </th>--}}
{{--                <th class="table-header-cell"> {{ __('translate.name') }} </th>--}}
{{--                <th class="table-header-cell"> {{ __('translate.email') }} </th>--}}
{{--                <th class="table-header-cell"> {{ __('translate.subscription') }} </th>--}}
{{--                <th class="table-header-cell"> {{ __('translate.confirmed') }} </th>--}}
{{--                <th class="table-header-cell"> {{ __('translate.locked') }} </th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            @forelse($users as $user)--}}
{{--                <tr class="table-body-row">--}}
{{--                    <td class="table-body-cell"> {{$user->id}} </td>--}}
{{--                    <td class="table-body-cell"> {{$user->name}} </td>--}}
{{--                    <td class="table-body-cell"> {{$user->email}} </td>--}}
{{--                    <td class="table-body-cell">--}}
{{--                        <select class="input-option w-full" id="subscription" name="subscription">--}}
{{--                            <option selected disabled> {{ $user->subscription == null ? "null" : $user->subscription->plan }} </option>--}}
{{--                            <option value="Gold"> Gold</option>--}}
{{--                            <option value="Silver"> Silver</option>--}}
{{--                            <option value="Bronze"> Bronze</option>--}}
{{--                        </select>--}}

{{--                    </td>--}}
{{--                    <td class="table-body-cell">--}}
{{--                        @if($user->confirmed == true)--}}
{{--                            <i class="fas fa-check text-green-600"></i>--}}
{{--                        @else--}}
{{--                            <i class="fas fa-ban text-red-600"></i>--}}
{{--                        @endif--}}
{{--                    </td>--}}
{{--                    <td class="table-body-cell">--}}
{{--                        @if($user->locked == true)--}}
{{--                            <i class="fas fa-ban text-red-600"></i>--}}
{{--                        @else--}}
{{--                            <i class="fas fa-check text-green-600"></i>--}}
{{--                        @endif--}}
{{--                    </td>--}}

{{--                </tr>--}}
{{--            @empty--}}
{{--                <p> User's table is empty</p>--}}
{{--            @endforelse--}}
{{--            </tbody>--}}
{{--        </table>--}}
{{--    </div>--}}

@endsection

