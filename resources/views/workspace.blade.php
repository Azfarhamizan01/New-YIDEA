@extends('layouts.app')

@section('content')
<section class="page-wrap workspace">
    <div class="profile-banner">
        <div class="avatar large">RP</div>
        <div class="profile-copy">
            <h2>Rian Pratama <span class="pro-badge">♕ PRO</span></h2>
            <p>Logo & Brand Identity · 4.95 Rating ⭐ (52 Finished Projects)</p>
        </div>
        <div class="profile-actions">
            <a class="btn outline" href="#">View Public Profile</a>
            <a class="btn primary" href="{{ route('projects') }}">Browse Design Briefs</a>
        </div>
    </div>

    <div class="wallet-grid">
        <div class="metric"><small>WALLET BALANCE</small><strong>Rp 14.250.000</strong><a href="#">Withdraw / View Ledger →</a></div>
        <div class="metric"><small>LIFETIME EARNINGS</small><strong class="green">Rp 0</strong><span>From 52 projects</span></div>
        <div class="metric"><small>ACTIVE WORKSPACES</small><strong class="purple">1</strong><span>Ongoing client deliverables</span></div>
        <div class="metric"><small>TIER STATUS</small><strong class="orange">DESIGNER PRO</strong><span class="green-text">5% Platform Fee Active</span></div>
    </div>

    <div class="workspace-grid">
        <div class="panel">
            <h3>▣ &nbsp; ACTIVE DELIVERABLES (1)</h3>
            <div class="deliverable">
                <div><small>Logo & Brand Identity &nbsp; <b>PAID</b></small><h4>Desain Brand Identity & Kemasan Kopi Artisan "Kopi Senja"</h4><p>Rp 3.500.000 · Client: Budi Santoso</p></div>
                <button class="btn primary" onclick="alert('Demo Chat Room')">▣ Chat Room</button>
            </div>
        </div>
        <div class="panel">
            <h3>✧ &nbsp; CURATED BRIEFS (0) <a href="{{ route('projects') }}">View All →</a></h3>
            <div class="empty-panel">Belum ada brief yang dikurasi untuk akun ini.</div>
        </div>
    </div>
</section>
@endsection
