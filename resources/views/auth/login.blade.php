@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="mx-4 w-full max-w-md rounded-3xl border border-white/40 bg-white/80 p-8 shadow-soft backdrop-blur-sm">
            <h2 class="text-2xl font-semibold">Masuk ke Stockifyy</h2>
            <p class="mt-2 text-sm text-ink/70">Akses dashboard inventaris Anda dengan akun yang sudah terdaftar.</p>

            <div class="mt-6">
                @include('auth._login_form')
            </div>

            <p class="mt-4 text-center text-sm text-ink/70">
                Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-mauve">Daftar</a>
            </p>
        </div>
    </div>
@endsection