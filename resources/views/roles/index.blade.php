@extends('dashboards._admin_header')
@section('index-content')
    <div class="grid grid-cols-6">
        <!-- left side -->
        <div class="grid col-span-2">
            @forelse( $roles as $role)
                <div class="box mt-1 overflow-auto">
                    <div class="grid">
                        <table class="pb-2">
                            <thead>
                            <tr class="table-header-row">
                                <th class="table-header-cell"> {{ $role->name }} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse( $role->permissions as $item)
                                <tr class="table-body-row">
                                    <td class="table-body-cell"> {{ $item->name }} </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="btn-circle-pink transform translate-x-5 -translate-y-5">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
        <!-- right side -->
        <div class="grid col-span-3 mt-2 ml-10">
            <div class="grid grid-rows-2 grid-flow-col">
                <div class="flex flex-col">
                    <table class="table-auto mb-2">
                        <thead class="sticky top-0">
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
                <div class="flex justify-end transform -translate-y-6 translate-x-5">
                    <div class="btn-circle-pink">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection