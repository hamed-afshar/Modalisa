@extends('layouts.app')
@section('content')
    <div class="bg-white shadow-lg">
        <div class="container grid grid-cols-1 md:grid-cols-2 bg-white mx-auto p-4">
            <div>
                <h1 class="logo-medium"> Modalisa </h1>
            </div>
            <div class="flex flex-wrap justify-end">
                <button class="btn-green md:w-1/4 lg:w-1/6 w-full block"> Login </button>
                <button class="btn-green mt-2 md:mt-0 md:ml-2 w-full md:w-1/4 lg:w-1/6"> Sign Up </button>
            </div>
        </div> <!-- end container -->
    </div>
@endsection
