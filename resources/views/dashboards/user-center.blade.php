<!-- admin direct to this page when click on user center tab-->
@extends('layouts.app')
@section('content')
    <div id="app">
        <admin-navbar></admin-navbar>
        <admin-header></admin-header>
        <!-- user table -->
        <div class="container mx-auto mt-10">
            <users-table></users-table>
        </div>
    </div>
@endsection

