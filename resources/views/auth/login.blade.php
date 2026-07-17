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
                    <span class="rounded-full bg-white px-9 py-3 text-sm font-bold tracking-widest text-mauve shadow-lg">
                        MASUK
                    </span>
                    <a href="{{ route('register') }}"
                        class="text-sm font-semibold tracking-[0.2em] text-white/80 transition hover:text-white">
                        DAFTAR
                    </a>
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
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <h2 class="mt-4 text-2xl font-bold tracking-wide text-mauve">MASUK</h2>
                    <p class="mt-1 text-center text-xs text-ink/60">Akses dashboard inventaris Stockifyy Anda</p>
                </div>

                <div class="mt-8">
                    @include('auth._login_form')
                </div>

                <p class="mt-6 text-center text-sm text-ink/70 md:hidden">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-mauve">Daftar</a>
                </p>
            </div>
        </div>
    </div>
@endsection