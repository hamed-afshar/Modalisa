@extends('layouts.app')
@section('content')
    <div id="app">
        <admin-navbar></admin-navbar>
        <admin-header></admin-header>
        <div class="container mx-auto mt-10">
            <div class="flex">
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
                    <subscriptions-table></subscriptions-table>
                </div>
            </div>
        </div>

    </div>
@endsection



