<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $transaction->invoice_code }}</title>
    <style>
        body {
            font-family: "Courier New", monospace;
            background: #f5f5f5;
            margin: 0;
            padding: 16px;
        }
        .receipt {
            width: 320px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            padding: 12px;
        }
        .center {
            text-align: center;
        }
        .divider {
            border-top: 1px dashed #444;
            margin: 8px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin: 2px 0;
        }
        .small {
            font-size: 12px;
        }
        .controls {
            width: 320px;
            margin: 0 auto 12px;
            display: flex;
            gap: 8px;
        }
        .btn {
            border: 1px solid #333;
            background: #fff;
            padding: 6px 10px;
            cursor: pointer;
            text-decoration: none;
            color: #000;
            font-size: 12px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt {
                border: none;
                width: 100%;
            }
            .controls {
                display: none;
            }
        }
    </style>
</head>
<body>
    @if (empty($isExport))
        <div class="controls">
            <button class="btn" onclick="window.print()">Print / Save PDF</button>
            <a href="{{ route('cashier.transactions.show', $transaction) }}" class="btn">Kembali</a>
        </div>
    @endif

    <div class="receipt small">
        <div class="center">
            <strong>Toko Arka</strong><br>
            Struk Pembayaran
        </div>

        <div class="divider"></div>

        <div>Invoice: {{ $transaction->invoice_code }}</div>
        <div>Tanggal: {{ $transaction->transaction_date?->format('d-m-Y H:i:s') }}</div>
        <div>Kasir: {{ $transaction->user?->name ?? 'User tidak ditemukan' }}</div>
        <div>Metode: {{ strtoupper($transaction->payment_method) }}</div>

        <div class="divider"></div>

        @foreach ($transaction->details as $detail)
            <div>{{ $detail->product?->name ?? '-' }}</div>
            <div class="row">
                <span>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach

        <div class="divider"></div>

        <div class="row">
            <span>Total</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Dibayar</span>
            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Kembalian</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>
        <div class="center">Terima kasih</div>
    </div>
</body>
</html>
