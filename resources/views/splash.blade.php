@extends('layouts.guest')

@section('content')
    <div class="absolute inset-0 overflow-hidden"
        style="background: linear-gradient(160deg, #F5F5F5 0%, #E5E5E5 35%, #BFBFBF 70%, #111111 100%);">

        {{-- Cahaya lembut menyebar, tanpa garis atau bentuk tajam --}}
        <div class="absolute inset-0"
            style="background: radial-gradient(circle at 22% 15%, rgba(255,255,255,0.35), transparent 55%);"></div>
        <div class="absolute inset-0"
            style="background: radial-gradient(circle at 85% 75%, rgba(80,20,45,0.25), transparent 60%);"></div>
        {{-- Sedikit gelap cuma di bagian bawah, secukupnya buat kontras slider --}}
        <div class="absolute inset-x-0 bottom-0 h-2/5 bg-gradient-to-t from-black/25 to-transparent"></div>

        {{-- Konten lockscreen --}}
        <div class="relative z-10 flex h-full flex-col items-center justify-center gap-20 px-6 py-14 sm:py-20">

            {{-- Logo dan judul --}}
            <div class="text-center">
                <h1 class="splash-title text-6xl font-extrabold tracking-tight text-white"
                    style="text-shadow: 0 2px 14px rgba(0,0,0,0.45);">
                    Stock<span class="logo-letter-wrapper">
                        <img src="{{ asset('images/logo (1).png') }}" alt="Logo kupu-kupu" class="logo-above"
                            onerror="this.style.display='none'">
                        <span class="logo-letter text-white">l</span>
                    </span>fy
                </h1>
                <p class="mt-5 text-lg text-white/90" style="text-shadow: 0 1px 8px rgba(0,0,0,0.3);">
                    Manajemen stok yang manis dan elegan
                </p>
            </div>

            {{-- Slide untuk masuk, gaya lockscreen --}}
            <a href="{{ route('login') }}" id="slide-to-enter"
                class="relative flex h-16 w-full max-w-xs select-none items-center overflow-hidden rounded-full bg-white/15 ring-1 ring-white/30 backdrop-blur-md">

                <span id="slide-label"
                    class="pointer-events-none absolute inset-0 flex items-center justify-center gap-1.5 text-sm font-medium tracking-wide text-white"
                    style="text-shadow: 0 1px 6px rgba(0,0,0,0.3);">
                    Geser untuk masuk
                    <span class="chevrons ml-1 flex">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                        <svg class="-ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </span>

                <span id="slide-thumb"
                    class="absolute left-1 top-1/2 z-10 flex h-12 w-12 items-center justify-center rounded-full bg-white text-mauve shadow-lg"
                    style="transform: translateY(-50%);">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            </a>
        </div>
    </div>

    <style>
        .logo-letter-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            margin: 0 2px;
        }

        .logo-above {
            position: absolute;
            top: -58px;
            left: 50%;
            transform: translateX(-50%) rotate(-18deg);
            width: 96px;
            height: auto;
            display: block;
            filter: brightness(0) invert(1);
        }

        .logo-letter {
            display: inline-block;
            font-size: 3.5rem;
            line-height: 1;
            transform: translateY(-4px);
        }

        .chevrons svg {
            animation: chevron-pulse 1.4s ease-in-out infinite;
        }

        .chevrons svg:nth-child(2) {
            animation-delay: 0.15s;
        }

        @keyframes chevron-pulse {

            0%,
            100% {
                opacity: 0.35;
                transform: translateX(0);
            }

            50% {
                opacity: 1;
                transform: translateX(3px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .chevrons svg {
                animation: none;
            }
        }
    </style>

    <script>
        (function () {
            const track = document.getElementById('slide-to-enter');
            const thumb = document.getElementById('slide-thumb');
            const label = document.getElementById('slide-label');
            if (!track || !thumb || !label) return;

            let dragging = false;
            let moved = false;
            let startX = 0;
            let currentX = 0;
            let maxX = 0;

            function getMaxX() {
                return track.offsetWidth - thumb.offsetWidth - 8;
            }

            function setThumbX(x) {
                thumb.style.transform = 'translateY(-50%) translateX(' + x + 'px)';
                label.style.opacity = String(Math.max(0, 1 - x / maxX));
            }

            function onDragStart(e) {
                dragging = true;
                moved = false;
                maxX = getMaxX();
                startX = (e.touches ? e.touches[0].clientX : e.clientX) - currentX;
                thumb.style.transition = 'none';
                e.preventDefault();
            }

            function onDragMove(e) {
                if (!dragging) return;
                const clientX = e.touches ? e.touches[0].clientX : e.clientX;
                let x = clientX - startX;
                x = Math.max(0, Math.min(x, maxX));
                if (Math.abs(x - currentX) > 3) moved = true;
                currentX = x;
                setThumbX(x);
                if (e.cancelable) e.preventDefault();
            }

            function onDragEnd() {
                if (!dragging) return;
                dragging = false;
                thumb.style.transition = 'transform 0.25s ease';
                label.style.transition = 'opacity 0.25s ease';

                if (currentX > maxX * 0.7) {
                    setThumbX(maxX);
                    setTimeout(function () {
                        window.location.href = track.getAttribute('href');
                    }, 180);
                } else {
                    currentX = 0;
                    setThumbX(0);
                }
            }

            thumb.addEventListener('mousedown', onDragStart);
            thumb.addEventListener('touchstart', onDragStart, { passive: false });
            window.addEventListener('mousemove', onDragMove);
            window.addEventListener('touchmove', onDragMove, { passive: false });
            window.addEventListener('mouseup', onDragEnd);
            window.addEventListener('touchend', onDragEnd);

            // Tap biasa (tanpa geser) tetap jalan lewat href bawaan <a>,
            // tapi kalau habis drag (berhasil atau dibatalkan) jangan double-navigate.
            track.addEventListener('click', function (e) {
                if (moved) e.preventDefault();
            });
        })();
    </script>
@endsection