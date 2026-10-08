<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkoutProject(Project $project)
    {
        abort_unless($project->client_id === Auth::id(), 403);
        abort_unless($project->winner_designer_id, 422, 'Pilih designer terlebih dahulu.');

        $transaction = Transaction::where('project_id', $project->id)
            ->where('type', 'escrow')
            ->whereIn('status', ['pending', 'held'])
            ->latest()
            ->first();

        if ($transaction?->status === 'held') {
            return back()->with('success', 'Pembayaran escrow untuk project ini sudah diterima.');
        }

        $transaction ??= Transaction::create([
            'project_id' => $project->id,
            'client_id' => Auth::id(),
            'designer_id' => $project->winner_designer_id,
            'amount' => $project->proposals()->where('status', 'accepted')->value('price') ?? $project->budget,
            'platform_fee' => $this->platformFee($project->budget, Auth::user()),
            'type' => 'escrow',
            'status' => 'pending',
        ]);

        return $this->midtrans($transaction, $project->title);
    }

    public function checkoutMembership(string $plan)
    {
        abort_unless(isset($this->membershipPrices()[$plan]), 404);
        $price = $this->membershipPrices()[$plan];

        if ($plan === 'free') {
            DB::transaction(function () {
                Membership::where('user_id', Auth::id())->where('status', 'active')->update([
                    'status' => 'expired',
                    'ends_at' => now(),
                ]);
                Membership::create([
                    'user_id' => Auth::id(),
                    'plan' => 'free',
                    'price' => 0,
                    'starts_at' => now(),
                    'ends_at' => null,
                    'status' => 'active',
                ]);
            });

            return back()->with('success', 'Paket Free aktif.');
        }

        $transaction = Transaction::create([
            'project_id' => null,
            'client_id' => Auth::id(),
            'designer_id' => null,
            'amount' => $price,
            'platform_fee' => 0,
            'type' => 'membership',
            'status' => 'pending',
            'membership_plan' => $plan,
        ]);

        return $this->midtrans($transaction, 'YIDEA PRO Membership - '.strtoupper($plan));
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $signature = hash('sha512', ($request->order_id ?? '').($request->status_code ?? '').($request->gross_amount ?? '').$serverKey);
        abort_unless($serverKey && hash_equals($signature, (string) $request->signature_key), 403);

        $transaction = Transaction::where('external_id', $request->order_id)->firstOrFail();
        $newStatus = $this->mapStatus($request->transaction_status, $request->fraud_status);

        DB::transaction(function () use ($transaction, $newStatus) {
            // Webhook Midtrans dapat dikirim berkali-kali. Jangan mengulang efek bisnis.
            if ($transaction->status !== 'held' || $newStatus !== 'held') {
                $transaction->update(['status' => $newStatus]);
            }

            if ($newStatus === 'held' && $transaction->type === 'escrow' && $transaction->project) {
                $transaction->project->update(['status' => 'paid']);
            }

            if ($newStatus === 'held' && $transaction->type === 'membership' && $transaction->membership_plan) {
                Membership::where('user_id', $transaction->client_id)
                    ->where('status', 'active')
                    ->update(['status' => 'expired', 'ends_at' => now()]);

                Membership::create([
                    'user_id' => $transaction->client_id,
                    'plan' => $transaction->membership_plan,
                    'price' => $transaction->amount,
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                    'status' => 'active',
                ]);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function checkStatus(Transaction $transaction)
{
    abort_unless($transaction->client_id === Auth::id(), 403);

    $serverKey = config('services.midtrans.server_key');

    abort_unless($serverKey, 500, 'MIDTRANS_SERVER_KEY belum diisi.');

    $base = config('services.midtrans.is_production')
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com';

    $response = Http::withBasicAuth($serverKey, '')
        ->get($base . '/v2/' . $transaction->external_id . '/status');

    if (!$response->successful()) {
        return back()->withErrors([
            'payment' => 'Gagal mengecek status pembayaran ke Midtrans.'
        ]);
    }

    $data = $response->json();

    $newStatus = $this->mapStatus(
        $data['transaction_status'] ?? null,
        $data['fraud_status'] ?? null
    );

    DB::transaction(function () use ($transaction, $newStatus) {

        $transaction->update([
            'status' => $newStatus
        ]);

        if (
            $newStatus === 'held' &&
            $transaction->type === 'escrow' &&
            $transaction->project
        ) {
            $transaction->project->update([
                'status' => 'paid'
            ]);
        }

        if (
            $newStatus === 'held' &&
            $transaction->type === 'membership' &&
            $transaction->membership_plan
        ) {
            Membership::where('user_id', $transaction->client_id)
                ->where('status', 'active')
                ->update([
                    'status' => 'expired',
                    'ends_at' => now()
                ]);

            Membership::create([
                'user_id' => $transaction->client_id,
                'plan' => $transaction->membership_plan,
                'price' => $transaction->amount,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);
        }
    });

    return back()->with(
        $newStatus === 'held' ? 'success' : 'info',
        $newStatus === 'held'
            ? 'Pembayaran berhasil dikonfirmasi.'
            : 'Status pembayaran saat ini: ' . $newStatus
    );
}



    public function completeProject(Project $project)
{
    abort_unless($project->client_id === Auth::id(), 403);

    abort_unless(
        in_array($project->status, ['paid', 'in_progress'], true),
        422,
        'Project belum siap diselesaikan.'
    );

    $transaction = Transaction::where('project_id', $project->id)
        ->where('type', 'escrow')
        ->where('status', 'held')
        ->latest()
        ->first();

    abort_unless(
        $transaction,
        422,
        'Pembayaran escrow belum berstatus held.'
    );

    DB::transaction(function () use ($project, $transaction) {

        // Ubah escrow menjadi released
        $transaction->update([
            'status' => 'released'
        ]);

        // Hitung uang yang diterima designer
        $payoutAmount = max(
            0,
            (float) $transaction->amount - (float) $transaction->platform_fee
        );

        // Catat transaksi payout
        Transaction::create([
            'project_id' => $project->id,
            'client_id' => $project->client_id,
            'designer_id' => $project->winner_designer_id,
            'amount' => $payoutAmount,
            'platform_fee' => 0,
            'type' => 'payout',
            'status' => 'released',
        ]);

        // TAMBAHKAN SALDO DESIGNER
        if ($project->winner) {
            $project->winner->increment('balance', $payoutAmount);

            // Tambah jumlah project yang selesai
            $project->winner->increment('completed_projects');
        }

        // Project selesai
        $project->update([
            'status' => 'completed'
        ]);
    });

    return back()->with(
        'success',
        'Project selesai. Saldo designer berhasil ditambahkan.'
    );
}

    private function midtrans(Transaction $transaction, string $itemName)
    {
        $serverKey = config('services.midtrans.server_key');
        if (!$serverKey) {
            return back()->withErrors(['payment' => 'MIDTRANS_SERVER_KEY belum diisi di .env.']);
        }

       $orderId = 'YIDEA-'.$transaction->id.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));

$transaction->update([
    'external_id' => $orderId,
]);

        $base = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';

        $response = Http::withBasicAuth($serverKey, '')
            ->post($base.'/snap/v1/transactions', [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $transaction->amount,
                ],
                'item_details' => [[
                    'id' => (string) $transaction->id,
                    'price' => (int) $transaction->amount,
                    'quantity' => 1,
                    'name' => Str::limit($itemName, 50, ''),
                ]],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ]);

       if (!$response->successful()) {
    $errorMessages = $response->json('error_messages');

    $detail = is_array($errorMessages)
        ? implode(', ', $errorMessages)
        : $response->body();

    return back()->withErrors([
        'payment' => 'Midtrans gagal membuat transaksi. HTTP '.$response->status().': '.$detail
    ]);
}

        return view('payments.snap', [
            'snapToken' => $response->json('token'),
            'clientKey' => config('services.midtrans.client_key'),
            'isProduction' => config('services.midtrans.is_production'),
            'transaction' => $transaction,
        ]);
    }

    private function mapStatus(?string $status, ?string $fraud): string
    {
        if ($status === 'capture' && $fraud === 'challenge') return 'pending';

        return match ($status) {
            'capture', 'settlement' => 'held',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'failed',
            default => 'pending',
        };
    }

    private function membershipPrices(): array
    {
        return ['free' => 0, 'pro' => 49000, 'studio' => 149000];
    }

    private function platformFee($amount, $user): float
    {
        $plan = $user->memberships()->where('status', 'active')->latest()->value('plan') ?? 'free';
        $rate = ['free' => .10, 'pro' => .05, 'studio' => .03][$plan] ?? .10;
        return round(((float) $amount) * $rate, 2);
    }
}
