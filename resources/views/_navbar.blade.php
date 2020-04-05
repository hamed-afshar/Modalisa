@extends('layouts.app')
@section('content')
    <div class="bg-white shadow-lg">
        <div class="container mx-auto flex bg-white p-2">
            <nav class="w-2/5">
                <a href="#" class="top-navbar-item"> <i class="fa fa-home fa-lg"></i> Home </a>
                <a href="#" class="top-navbar-item ml-8"> <i class="fab fa-whatsapp fa-lg>"></i> Contact Us </a>
            </nav>
            <div class="w-1/5">
                <h1 class="text-center font-extrabold text-xl"> Modalisa </h1>
            </div>
            <div class="w-2/5 flex justify-end">
                <a href="#" class="ml-4 top-navbar-item"><i class="fa fa-bell fa-lg"></i> </a>
                <a href="#" class="ml-4 top-navbar-item"><i class="fa fa-envelope fa-lg"></i> </a>
                <a href="#" class="ml-12 top-navbar-item"><i class="fa fa-user fa-lg"></i> </a>
            </div>
        </div> <!-- end container -->
    </div>
@endsection

