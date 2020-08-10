@extends('dashboards._admin_header')
@section('index-content')
    <div id="app" class="grid grid-cols-6">
        <!-- left side -->
        <div class="grid col-span-3 mt-2">
            <div class="flex flex-col">
                <div class="box-header"> {{ __("translate.role") }}</div>
                <roles-table></roles-table>
            </div>
        </div>
        <!-- right side -->
        <div class="grid col-span-3 mt-2 ml-10">
            <div class="grid grid-rows-2 grid-flow-col">
                <div class="flex flex-col">
                    <div class="box-header"> {{ __("translate.permission") }}</div>
                    <permission-table></permission-table>
                </div>
            </div>
        </div>
    </div>
@endsection

