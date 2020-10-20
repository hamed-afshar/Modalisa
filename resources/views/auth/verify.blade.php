@extends('layouts.app')
@section('content')
    <div id="app">
        <div class="container max-w-full mx-auto">
            <div class="flex justify-around py-24">
                <div class="box py-8 px-6 shadow-2xl">
                    <div class="flex flex-wrap">
                        <div class="w-full flex justify-end">
                            <a href="{{ auth()->logout() }}"> <i class="fas fa-times cursor-pointer"></i> </a>
                        </div>
                        <div class="w-full text-center font-bold text-lg">
                            {{ __('translate.verify_email') }}
                        </div>
                        <div class="w-full text-center font-bold text-pink-600">
                            {{ __('translate.verify_email_sentence') }}
                        </div>
                        <div class="w-full">
                            <div class="flex justify-center">
                                <form class="w-1/3" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button class="w-full btn-pink text-sm font-bold mt-2 px-3 py-2" type="submit">
                                        {{ __('translate.verify_again') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-center">
                                @if(session('resent'))
                                    <div class="success">
                                        {{ __('translate.request_sent') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

