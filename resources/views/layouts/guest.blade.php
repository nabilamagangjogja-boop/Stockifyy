<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockifyy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        blush: '#F8E8EE',
                        rose: '#E7C7D6',
                        mauve: '#CFA7B8',
                        ink: '#4A3B47',
                        cream: '#FFFDF9',
                    },
                    boxShadow: {
                        soft: '0 12px 40px rgba(120, 92, 118, 0.12)',
                    }
                }
            }
        }
    </script>
    <style>
        .butterfly-flight {
            position: absolute;
            width: 220px;
            height: auto;
            animation: fly 5s cubic-bezier(.22, .9, .35, 1) infinite;
            transform-origin: center;
            will-change: transform, opacity;
        }

        @keyframes fly {
            0% {
                transform: translate(-25vw, 40vh) rotate(-20deg) scale(0.8);
                opacity: 0
            }

            20% {
                opacity: 1
            }

            40% {
                transform: translate(10vw, 10vh) rotate(10deg) scale(1);
            }

            60% {
                transform: translate(45vw, 18vh) rotate(-10deg) scale(1.05);
            }

            80% {
                transform: translate(70vw, 6vh) rotate(15deg) scale(0.95);
            }

            100% {
                transform: translate(95vw, -4vh) rotate(0deg) scale(0.9);
                opacity: 0
            }
        }

        .splash-title {
            font-family: 'Figtree', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-cream via-blush to-rose text-ink">
    <main class="relative w-full h-screen overflow-hidden flex items-center justify-center">
        @yield('content')
    </main>
</body>

</html>