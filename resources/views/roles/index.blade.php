@extends('dashboards._admin_header')
@section('index-content')
    <div class="grid grid-cols-6">
        <!-- left side -->
        <div class="grid col-span-2 mt-2">
            <div class="flex flex-col">
                <div class="box-header"> {{ __("translate.role") }}</div>
                @forelse( $roles as $role)
                    <table class="table-auto">
                        <tbody class="w-full">
                        <tr class="table-body-row">
                            <td class="table-body-cell">
                                <div class="flex flex-row">
                                    <div class="w-5/6">
                                        <a href="{{ $role->path() }}" class="link">
                                            {{ $role->name }}
                                        </a>
                                    </div>
                                    <div class="w-1/6">
                                        <input type="checkbox">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                @empty
                @endforelse
                <div class="flex justify-end">
                    <div class="btn-circle-pink transform -translate-y-6 translate-x-5">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>

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
                                <td class="table-body-cell">
                                    <div class="flex">
                                        <div class="w-5/6">
                                            <a href="{{$permission->path()}}" class="link"> {{ $permission->name }} </a>
                                        </div>
                                        <div class="w-1/6">
                                            <input type="checkbox">
                                        </div>

                                    </div>

                                </td>
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