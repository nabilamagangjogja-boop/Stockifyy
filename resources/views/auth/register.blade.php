@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex items-center justify-center bg-blue-50 p-4">
        <div class="relative flex w-full max-w-3xl rounded-[2rem] bg-white shadow-2xl">

            {{-- Diagonal accent panel --}}
            <div class="relative hidden w-2/5 shrink-0 overflow-hidden rounded-l-[2rem] md:block">
                <div class="absolute inset-0"
                    style="background: linear-gradient(160deg, #F5F5F5 0%, #E5E5E5 30%, #BFBFBF 65%, #111111 100%);">
                </div>
                <div class="absolute inset-0"
                    style="background: radial-gradient(circle at 20% 15%, rgba(255,255,255,0.35), transparent 55%);">
                </div>

                <div class="relative z-10 flex h-full flex-col items-center justify-center gap-8 px-6">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold tracking-[0.2em] text-white/80 transition hover:text-white">
                        MASUK
                    </a>
                    <span class="rounded-full bg-white px-9 py-3 text-sm font-bold tracking-widest text-mauve shadow-lg">
                        DAFTAR
                    </span>
                </div>
            </div>

            {{-- Form panel --}}
            <div class="w-full px-8 py-10 sm:px-12 md:w-3/5">
                <div class="flex flex-col items-center">
                    <div
                        class="flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-mauve to-blush shadow-lg">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-wide text-mauve">DAFTAR</h2>
                    <p class="mt-1 text-center text-xs text-ink/60">Buat akun baru untuk Stockifyy</p>
                </div>

                <div class="mt-8">
                    @include('auth._register_form')
                </div>

                <p class="mt-6 text-center text-sm text-ink/70 md:hidden">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-semibold text-mauve">Masuk</a>
                </p>
            </div>
        </div>
    </div>
@endsection