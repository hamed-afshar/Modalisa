@extends('layouts.app')
@section('content')
    <div id="app">
        <div class="container max-w-full mx-auto">
            <div class="flex justify-around py-24">
                <div class="box py-8 px-6 shadow-2xl">
                    <div class="flex flex-wrap">
                        <div class="w-full flex justify-end">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-times cursor-pointer"></i> </a>
                        </div>
                        <!-- logout form is needed for x button-->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <div class="w-full text-center font-bold text-lg">
                            {{ __('translate.locked') }}
                        </div>
                        <div class="w-full text-center font-bold text-pink-600">
                            {{ __('translate.locked_sentence') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

