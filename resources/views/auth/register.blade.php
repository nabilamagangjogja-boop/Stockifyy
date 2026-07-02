@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="mx-4 w-full max-w-md rounded-3xl border border-white/40 bg-white/80 p-8 shadow-soft backdrop-blur-sm">
            <h2 class="text-2xl font-semibold">Buat Akun Baru</h2>
            <div class="mt-6">
                @include('auth._register_form')
            </div>
        </div>
    </div>
@endsection