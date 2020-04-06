@extends('layouts.app')
@section('content')
    <div class="flex flex-wrap">
        <div class="bg-gray-400 shadow-2xl h-96 w-full">

        </div>
        <div class="flex flex-wrap bg-white shadow-2xl w-full">
            <div class="w-full">
                <h1 class="logo-large text-center text-"> Modalisa </h1>
                <hr class="border-pink-600">
            </div>
            <div class="flex md:justify-end justify-center logo-small md:pr-5 py-2 md:py-0 w-full md:w-1/2">
                <a href="#"
                   class="border-b-2 border-solid border-pink-600 md:border-transparent hover:text-pink-600 hover:border-pink-600">
                    Login </a>
            </div>
            <div class="flex md:justify-start justify-center logo-small  md:pl-3 py-2 md:py-0 w-full md:w-1/2">
                <a href="#"
                   class="border-b-2 border-solid border-pink-600 md:border-transparent hover:text-pink-600 hover:border-pink-600">
                    Sign-up </a>
            </div>
        </div>
        <div class="bg-white flex shadow-2xl w-full">
            <div class="flex-1"></div>
            <nav class="md:flex-1 sm:w-full text-center pt-5">
                <a href="$" class="hover:text-pink-600 pr-2" ><i class="fab fa-instagram fa-lg"></i> </a>
                <a href="$" class="hover:text-pink-600 pr-2"><i class="far fa-envelope fa-lg"></i> </a>
                <a href="$" class="hover:text-pink-600 pr-2"><i class="fab fa-whatsapp fa-lg"></i> </a>
                <a href="$" class="hover:text-pink-600 pr-2"><i class="fab fa-telegram-plane fa-lg"></i> </a>

            </nav>
            <div class="flex-1"></div>

        </div>
    </div>

@endsection

