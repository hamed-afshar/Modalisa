@extends('layouts.app')
@section('content')
    <div id="app">
        <auth-navbar></auth-navbar>
        <admin-header></admin-header>
        <!-- user table -->
        <div class="container mx-auto mt-10">
            <users-table></users-table>
        </div>
    </div>
@endsection

