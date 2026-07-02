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
</head>

<body class="min-h-screen bg-gradient-to-br from-cream via-blush to-rose text-ink">
    <div class="min-h-screen">
        <nav class="border-b border-white/60 bg-white/70 backdrop-blur-md">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <a href="{{ route('dashboard') }}" class="text-xl font-semibold tracking-wide">Stockifyy</a>
                <div class="flex flex-wrap items-center gap-2 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="rounded-full px-4 py-2 hover:bg-blush">Dashboard</a>
                    <a href="{{ route('categories.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Kategori</a>
                    <a href="{{ route('products.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Produk</a>
                    <a href="{{ route('suppliers.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Supplier</a>
                    <a href="{{ route('transactions.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Stok</a>
                    <a href="{{ route('reports.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Laporan</a>
                    <a href="{{ route('users.index') }}" class="rounded-full px-4 py-2 hover:bg-blush">Pengguna</a>
                    @auth
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="rounded-full bg-ink px-4 py-2 text-white">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full bg-ink px-4 py-2 text-white">Masuk</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="mx-auto max-w-7xl px-6 py-8">
            @if(session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>

</html>