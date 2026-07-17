@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex items-center justify-center bg-ink p-4">
        <div class="relative w-full max-w-md rounded-[2rem] bg-white p-8 shadow-2xl sm:p-10">

            <div class="flex flex-col items-center text-center">
                <div
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-mauve to-blush shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a1.5 1.5 0 0 0 1.5-1.5v-8.25a1.5 1.5 0 0 0-1.5-1.5H6.75a1.5 1.5 0 0 0-1.5 1.5v8.25a1.5 1.5 0 0 0 1.5 1.5Z" />
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold tracking-wide text-mauve">RESET PASSWORD</h2>
                <p class="mt-1 text-sm text-ink/60">
                    Buat password baru untuk akun kamu.
                </p>
            </div>

            <div class="mt-8">
                @include('auth._reset_password_form')
            </div>
        </div>
    </div>
@endsection