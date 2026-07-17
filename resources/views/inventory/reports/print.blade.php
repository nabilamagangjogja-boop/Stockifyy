<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi — Stockify</title>
    <style>
        @page {
            margin: 16mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #111111;
            background: #ffffff;
            line-height: 1.4;
        }

        .page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 24px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 14px;
            margin-bottom: 22px;
        }

        .header img {
            height: 44px;
            width: 44px;
            object-fit: contain;
        }

        .header h1 {
            font-size: 22px;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .header p {
            font-size: 13px;
            color: #4b5563;
            margin: 4px 0 0;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
            font-size: 12px;
            color: #4b5563;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 16px;
        }

        th,
        td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        thead th {
            background: #f9fafb;
            color: #111111;
            font-weight: 700;
            border-bottom: 2px solid #d1d5db;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 72px;
            padding: 4px 10px;
            border-radius: 999px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .status-pending {
            background: #4b5563;
        }

        .status-ok {
            background: #111111;
        }

        .status-rejected {
            background: #6b7280;
        }

        .footer {
            margin-top: 26px;
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }

        .print-btn {
            margin: 0 0 16px;
            padding: 10px 20px;
            background: #111111;
            color: white;
            border: none;
            border-radius: 999px;
            font-size: 14px;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body,
            .page {
                margin: 0;
                padding: 0;
                box-shadow: none;
                background: transparent;
            }

            .page {
                padding: 0;
            }

            .header,
            table,
            .meta,
            .footer {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>

        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Stockify">
            <div>
                <h1>Laporan Transaksi Stok — Stockify</h1>
                <p>Dicetak oleh {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
            </div>
        </div>

        <div class="meta">
            <span>
                @if(request('date_from') || request('date_to'))
                    Periode: {{ request('date_from') ?: 'Awal' }} — {{ request('date_to') ?: 'Sekarang' }}
                @else
                    Periode: Semua data
                @endif
                @if(request('type')) · Tipe: {{ request('type') }} @endif
            </span>
            <span>Dicetak: {{ now()->format('d M Y, H:i') }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Dicatat Oleh</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr>
                        <td>{{ $t->date->format('d M Y') }}</td>
                        <td>{{ $t->product->name ?? '-' }}</td>
                        <td>{{ $t->product->category->name ?? '-' }}</td>
                        <td>{{ $t->type }}</td>
                        <td>{{ $t->quantity }}</td>
                        <td>
                            <span
                                class="status {{ $t->status === 'Pending' ? 'status-pending' : (in_array($t->status, ['Diterima', 'Dikeluarkan']) ? 'status-ok' : 'status-rejected') }}">
                                {{ $t->status }}
                            </span>
                        </td>
                        <td>{{ $t->user->name ?? '-' }}</td>
                        <td>{{ $t->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:20px; color:#999;">Tidak ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="footer">Total {{ $transactions->count() }} transaksi · Dibuat otomatis oleh sistem Stockify</p>
    </div>
</body>

</html>