<div class="mx-auto max-w-3xl">

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo (1).png') }}" alt="Logo Stockifyy"
                class="h-16 w-16 rounded-3xl border border-white bg-white/80 shadow-soft"
                onerror="this.style.display='none'" />
            <div>
                <p class="text-sm" style="color:var(--ink-soft)">Selamat datang kembali,</p>
                <h1 class="font-display text-4xl leading-tight" style="color:var(--ink)">
                    {{ auth()->user()->name }} 👋
                </h1>
                <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-medium"
                    style="background:var(--blush); color:var(--rose-dark)">
                    Staff Gudang
                </span>
            </div>
        </div>
        <div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-full font-display text-xl text-white shrink-0"
            style="background:var(--rose-dark)">
            @if(auth()->user()->photo)
                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto profil"
                    class="h-full w-full object-cover">
            @else
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            @endif
        </div>
    </div>

    <p class="mt-6 text-sm" style="color:var(--ink-soft)">Berikut daftar tugas yang perlu kamu selesaikan hari ini.</p>

    {{-- Barang masuk perlu diperiksa --}}
    <div class="mt-6">
        <div class="mb-3 flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold text-white"
                style="background:#8FBF8A">{{ $barangMasukPerlu->count() }}</span>
            <h3 class="font-display text-lg" style="color:var(--ink)">Barang Masuk Perlu Diperiksa</h3>
        </div>
        <div class="space-y-3">
            @forelse($barangMasukPerlu as $transaction)
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white bg-white/80 px-5 py-4 shadow-soft">
                    <div class="min-w-0">
                        <p class="truncate font-medium" style="color:var(--ink)">{{ $transaction->product->name ?? '-' }}
                        </p>
                        <p class="text-xs" style="color:var(--ink-soft)">{{ $transaction->quantity }} unit ·
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }} · dicatat oleh
                            {{ $transaction->user->name ?? 'Sistem' }}
                        </p>
                    </div>
                    <div class="flex shrink-0 gap-2">
                        <form method="POST" action="{{ route('transactions.confirm', $transaction) }}">
                            @csrf
                            <button class="rounded-full px-4 py-2 text-xs font-semibold text-white"
                                style="background:#8FBF8A">Terima</button>
                        </form>
                        <form method="POST" action="{{ route('transactions.reject', $transaction) }}">
                            @csrf
                            <button class="rounded-full px-4 py-2 text-xs font-semibold"
                                style="background:var(--blush); color:var(--rose-dark)">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed p-6 text-center text-sm"
                    style="border-color:var(--line); color:var(--ink-soft)">
                    Tidak ada barang masuk yang perlu diperiksa 🎉
                </div>
            @endforelse
        </div>
    </div>

    {{-- Barang keluar perlu disiapkan --}}
    <div class="mt-8">
        <div class="mb-3 flex items-center gap-2">
            <span class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold text-white"
                style="background:#C97A9D">{{ $barangKeluarPerlu->count() }}</span>
            <h3 class="font-display text-lg" style="color:var(--ink)">Barang Keluar Perlu Disiapkan</h3>
        </div>
        <div class="space-y-3">
            @forelse($barangKeluarPerlu as $transaction)
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-white bg-white/80 px-5 py-4 shadow-soft">
                    <div class="min-w-0">
                        <p class="truncate font-medium" style="color:var(--ink)">{{ $transaction->product->name ?? '-' }}
                        </p>
                        <p class="text-xs" style="color:var(--ink-soft)">{{ $transaction->quantity }} unit ·
                            {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }} · dicatat oleh
                            {{ $transaction->user->name ?? 'Sistem' }}
                        </p>
                    </div>
                    <div class="flex shrink-0 gap-2">
                        <form method="POST" action="{{ route('transactions.confirm', $transaction) }}">
                            @csrf
                            <button class="rounded-full px-4 py-2 text-xs font-semibold text-white"
                                style="background:#C97A9D">Sudah Dikirim</button>
                        </form>
                        <form method="POST" action="{{ route('transactions.reject', $transaction) }}">
                            @csrf
                            <button class="rounded-full px-4 py-2 text-xs font-semibold"
                                style="background:var(--blush); color:var(--rose-dark)">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed p-6 text-center text-sm"
                    style="border-color:var(--line); color:var(--ink-soft)">
                    Tidak ada barang keluar yang perlu disiapkan 🎉
                </div>
            @endforelse
        </div>
    </div>
</div>