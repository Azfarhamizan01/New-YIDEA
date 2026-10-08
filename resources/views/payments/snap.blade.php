@extends('layouts.app')

@section('content')
<section class="auth-page">
    <div class="auth-card payment-card">

        <div class="eyebrow gold">🔒 SECURE PAYMENT</div>

        <h1>Konfirmasi Pembayaran</h1>

        <p>
            Transaksi dibuat melalui YIDEA PRO Escrow.
            Klik tombol di bawah untuk membuka halaman pembayaran Midtrans.
        </p>

        <div class="payment-summary">
            <span>Order</span>
            <b>{{ $transaction->external_id }}</b>

            <span>Total</span>
            <strong>
                Rp {{ number_format($transaction->amount,0,',','.') }}
            </strong>
        </div>

        <button id="pay-button" class="btn primary full">
            Bayar Sekarang →
        </button>

        <a
            href="{{ route('payments.check-status', $transaction) }}"
            class="btn full"
            style="margin-top:10px; text-align:center;"
        >
            Cek Status Pembayaran
        </a>

        <small class="payment-note">
            Mode: {{ $isProduction ? 'Production' : 'Sandbox / Testing' }}
        </small>

    </div>
</section>

<script
    src="{{ $isProduction
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ $clientKey }}">
</script>

<script>
document.getElementById('pay-button').addEventListener('click', function(){

    window.snap.pay(@json($snapToken), {

        onSuccess: function(){
            window.location.href =
                @json(route('dashboard')) + '?payment=success';
        },

        onPending: function(){
            alert('Pembayaran menunggu konfirmasi.');
        },

        onError: function(){
            alert('Pembayaran gagal. Silakan coba lagi.');
        },

        onClose: function(){
            console.log('Payment popup ditutup.');
        }

    });

});
</script>

@endsection