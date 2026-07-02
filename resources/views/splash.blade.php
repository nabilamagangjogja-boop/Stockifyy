@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex flex-col items-center justify-center gap-8 overflow-hidden">

        {{-- Bagian logo dan judul --}}
        <div class="relative z-10 text-center">

            {{-- Nama aplikasi dengan logo kupu-kupu sebagai huruf i --}}
            <h1 class="splash-title text-6xl font-extrabold tracking-tight">
                Stock<span class="logo-letter-wrapper">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo kupu-kupu" class="logo-above">
                    <span class="logo-letter">l</span>
                </span>fy
            </h1>

            <p class="mt-5 text-lg text-ink/70">
                Manajemen stok yang manis dan elegan
            </p>
        </div>

        {{-- Tombol Masuk dan Daftar --}}
        <div class="relative z-10 mt-6 flex gap-6">
            <a href="{{ route('login') }}"
                class="bubble-btn relative inline-flex items-center justify-center rounded-full bg-white/90 px-6 py-3 shadow-soft">
                <span class="text-ink font-semibold">Masuk</span>
                <span class="bubble absolute -right-3 -top-2 h-6 w-6 rounded-full bg-rose/80"></span>
            </a>

            <a href="{{ route('register') }}"
                class="bubble-btn relative inline-flex items-center justify-center rounded-full bg-white/90 px-6 py-3 shadow-soft">
                <span class="text-ink font-semibold">Daftar</span>
                <span class="bubble absolute -right-3 -top-2 h-6 w-6 rounded-full bg-blush/80"></span>
            </a>
        </div>
    </div>

    <style>
        .bubble {
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        .bubble-btn:hover .bubble {
            transform: translateY(-10px) scale(1.05);
            opacity: 0.95;
        }

        .logo-letter-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            margin: 0 -2px;
        }

        .logo-above {
            position: absolute;
            top: -32px;
            left: 50%;
            transform: translateX(-50%) rotate(-18deg);
            width: 62px;
            height: auto;
            display: block;
        }

        .logo-letter {
            display: inline-block;
            font-size: 3.5rem;
            line-height: 1;
            color: #9e3f54;
            transform: translateY(-4px);
        }

        .splash-title {
            color: #9e3f54;
        }
    </style>
@endsection