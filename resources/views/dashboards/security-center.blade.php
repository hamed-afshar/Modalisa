@extends('dashboards._admin_header')
@section('index-content')
    <div id="app" class="flex flex-col md:flex-col lg:flex-row">
        <!-- left side -->
        <div class="lg:w-1/2">
            <div class="box-header w-full"> {{ __("translate.role") }}</div>
            <roles-table></roles-table>
        </div>
        <!-- right side -->
        <div class="lg:w-1/2 lg:ml-10">
            <div class="box-header w-full"> {{ __("translate.permission") }}</div>
            <permission-table></permission-table>
        </div>
    </div>
@endsection

