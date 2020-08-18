@extends('dashboards._admin_header')
@section('index-content')
    <div id="app" class="flex flex-col md:flex-col lg:flex-row">
        <!-- left side roles -->
        <div class="lg:w-2/5">
            <div class="box-header w-full"> {{ trans_choice("translate.role" , 2) }} </div>
            <roles-table></roles-table>
        </div>
        <!-- center permissions-->
        <div class="lg:w-2/5 lg:ml-10">
            <div class="box-header w-full"> {{ trans_choice("translate.permission" , 2) }} </div>
            <permission-table></permission-table>
        </div>
        <!-- right side subscriptions-->
        <div class="lg:w-1/5 lg:ml-10">
            <div class="box-header w-full"> {{ trans_choice("translate.subscription" , 2) }}</div>
            <subscriptions-table> </subscriptions-table>
        </div>
    </div>
@endsection

