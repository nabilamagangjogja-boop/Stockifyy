@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex items-center justify-center bg-ink p-4">
        <div class="relative w-full max-w-md rounded-[2rem] bg-white p-8 shadow-2xl sm:p-10">

            {{-- Tombol kembali ke login --}}
            <a href="{{ route('login') }}"
                class="absolute left-4 top-4 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-cream text-ink/60 shadow-sm transition hover:bg-blush hover:text-mauve">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span class="sr-only">Kembali</span>
            </a>

            <div class="flex flex-col items-center text-center">
                <div
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-mauve to-blush shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </div>
                <h2 class="mt-4 text-2xl font-bold tracking-wide text-mauve">LUPA PASSWORD</h2>
                <p class="mt-1 text-sm text-ink/60">
                    Masukkan email akun kamu, kami kirim link untuk reset password.
                </p>
            </div>

            <div class="mt-8">
                @include('auth._forgot_password_form')
            </div>

            <p class="mt-6 text-center text-sm text-ink/70">
                Ingat password kamu?
                <a href="{{ route('login') }}" class="font-semibold text-mauve">Masuk</a>
            </p>
        </div>
    </div>
@endsection