@extends('dashboards._admin_header')
@section('index-content')
    <div class="grid grid-rows-1">
        <div class="grid grid-cols-6">
            <div class="grid col-span-2">
                @forelse( $roles as $role)
                    <div class="box mt-1">
                        <div class="box-header">
                            <h1 class="text-white font-bold"> {{ $role->name }}</h1>
                        </div>
                        <div class="grid grid-rows-3">
                            <div class="grid row-start-3 w-full">
                                <button class="btn-green px-1 py-1.5"> Add Permission</button>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="grid col-span-3 mb-3">
                <div class="grid box w-full ml-4 mt-1">
                    <table class="table-auto">
                        <thead>
                        <tr class="table-header-row">
                            <th class="table-header-cell"> {{ __("translate.id") }}</th>
                            <th class="table-header-cell"> {{ __("translate.permission") }}</th>
                            <th class="table-header-cell"> {{ __("translate.alias") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($permissions as $permission)
                            <tr class="table-body-row">
                                <td class="table-body-cell"> {{ $permission->id }} </td>
                                <td class="table-body-cell"> {{ $permission->name }} </td>
                                <td class="table-body-cell"> {{ $permission->lable }} </td>
                            </tr>
                        @empty
                            <p> nothing </p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection