@extends('layouts.app')

@section('content')
<div class="project-page">

    <a href="{{ route('projects.index') }}" class="back-project">
        ← &nbsp; Back to Project Briefs
    </a>

    <div class="project-layout">

        {{-- LEFT CARD --}}
        <aside class="project-summary-card">

            <div class="project-summary-top">
                <span class="project-category">
                    {{ strtoupper($project->category ?? 'DESIGN PROJECT') }}
                </span>

                <span class="project-status">
                    {{ strtoupper($project->status ?? 'PUBLISHED') }}
                </span>
            </div>

            <h1 class="project-title">
                {{ $project->title }}
            </h1>

            <p class="project-date">
                Published {{ $project->created_at?->format('d F Y') }}
            </p>

            <div class="budget-card">

                <div>
                    <span class="meta-label">ESCROW BUDGET</span>

                    <strong class="budget-value">
                        Rp {{ number_format((float) $project->budget, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="deadline-box">
                    <span class="meta-label">DEADLINE</span>

                    <strong>
                        ◷ {{ $project->timeline_days ?? 7 }} Days
                    </strong>
                </div>

            </div>

            <div class="summary-divider"></div>

            <div class="creator-box">
                <span class="meta-label">PROJECT CREATOR</span>

                <div class="creator-info">

                    <div class="creator-avatar">
                        @if($project->client?->avatar)
                            <img
                                src="{{ asset('storage/' . $project->client->avatar) }}"
                                alt="{{ $project->client->name }}"
                            >
                        @else
                            {{ strtoupper(substr($project->client->name ?? 'C', 0, 1)) }}
                        @endif
                    </div>

                    <div class="creator-detail">
                        <strong>
                            {{ $project->client->name ?? 'Client' }}
                        </strong>

                        <div class="creator-rating">
                            <span>⭐</span>

                            <span>
                                {{ number_format((float) ($project->client->rating ?: 4.9), 1) }}
                            </span>

                            <span>· Verified Client</span>
                        </div>
                    </div>

                </div>
            </div>

        </aside>

        {{-- RIGHT CONTENT --}}
        <main class="project-main-content">

            <section class="project-content-card">

                <h2>Scope &amp; Requirements</h2>

                <p class="project-description">
                    {{ $project->description }}
                </p>

                <div class="style-preferences">
                    <span class="meta-label">STYLE PREFERENCES</span>

                    <div class="project-tags">
                        @if(is_array($project->tags) && count($project->tags))
                            @foreach($project->tags as $tag)
                                <span>#{{ ltrim($tag, '#') }}</span>
                            @endforeach
                        @else
                            <span>No specific preferences</span>
                        @endif
                    </div>
                </div>

            </section>

            <section class="project-content-card proposals-card">

                <div class="proposals-heading">
                    <h2>
                        ✧ &nbsp; Designer Concepts &amp; Pitches
                        ({{ $project->proposals->count() }})
                    </h2>

                    <p>
                        Review creative concepts submitted by designers and pick the winning proposal.
                    </p>
                </div>

                @auth
    @if(auth()->user()->isDesigner()
        && auth()->id() !== $project->client_id
        && in_array($project->status, ['published', 'proposals_received']))

        <div class="proposal-form-card">

            <div class="proposals-heading">
                <h2>✦ Submit Your Proposal</h2>

                <p>
                    Tawarkan harga, estimasi pengerjaan, dan jelaskan
                    bagaimana kamu akan mengerjakan project ini.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('proposals.store', $project) }}"
                class="proposal-form"
            >
                @csrf

                <div class="form-grid">

                    <label>
                        Harga Proposal
                        <input
                            type="number"
                            name="price"
                            min="10000"
                            required
                            value="{{ old('price') }}"
                            placeholder="Contoh: 2500000"
                        >
                    </label>

                    <label>
                        Waktu Pengerjaan
                        <input
                            type="number"
                            name="delivery_days"
                            min="1"
                            max="365"
                            required
                            value="{{ old('delivery_days') }}"
                            placeholder="Contoh: 7"
                        >
                    </label>

                </div>

                <label>
                    Cover Letter / Penawaran

                    <textarea
                        name="cover_letter"
                        rows="6"
                        minlength="20"
                        maxlength="5000"
                        required
                        placeholder="Jelaskan pengalaman, konsep, dan alasan kamu cocok mengerjakan project ini..."
                    >{{ old('cover_letter') }}</textarea>
                </label>

                <button type="submit" class="btn primary">
                    Submit Proposal →
                </button>

            </form>

        </div>

    @endif
@endauth

                @forelse($project->proposals as $proposal)

                    <article class="proposal-item">

                        <div class="proposal-designer">
                            <div class="proposal-avatar">
                                @if($proposal->designer?->avatar)
                                    <img
                                        src="{{ asset('storage/' . $proposal->designer->avatar) }}"
                                        alt="{{ $proposal->designer->name }}"
                                    >
                                @else
                                    {{ strtoupper(substr($proposal->designer->name ?? 'D', 0, 1)) }}
                                @endif
                            </div>

                            <div>
                                <strong>
                                    {{ $proposal->designer->name ?? 'Designer' }}
                                </strong>

                                <p>
                                    {{ $proposal->designer->specialty ?? 'Creative Designer' }}
                                </p>

                                <span>
                                    ⭐ {{ number_format((float) ($proposal->designer->rating ?: 4.9), 1) }}
                                </span>
                            </div>
                        </div>

                        <div class="proposal-description">
                            <span class="meta-label">PROPOSAL</span>

                            <p>
                                {{ $proposal->cover_letter ?? 'No proposal description available.' }}
                            </p>
                        </div>

                        <div class="proposal-price">
    <span class="meta-label">PROPOSAL PRICE</span>

    <strong>
        Rp {{ number_format((float) $proposal->price, 0, ',', '.') }}
    </strong>

    <span>
        {{ $proposal->delivery_days }} Days
    </span>

    {{-- PILIH DESIGNER --}}
    @auth
        @if(
            auth()->id() === $project->client_id &&
            $project->status === 'proposals_received' &&
            $proposal->status !== 'accepted'
        )
            <form
                method="POST"
                action="{{ route('proposals.accept', [$project, $proposal]) }}"
                class="inline-form"
                onsubmit="return confirm('Pilih {{ $proposal->designer->name ?? 'designer ini' }} sebagai designer project?');"
            >
                @csrf
                <button type="submit" class="btn primary">✓ Pilih Designer</button>
            </form>
        @endif
    @endauth
</div>

                    </article>

                @empty

                    <div class="empty-proposals">

                        <div class="empty-icon">
                            ◇
                        </div>

                        <strong>
                            No proposals submitted yet
                        </strong>

                        <p>
                            This brief was recently listed. Notifications have been dispatched to
                            qualified creators.
                        </p>

                    </div>

                @endforelse

            </section>

            {{-- PAYMENT / ESCROW --}}
@auth
    @if(auth()->id() === $project->client_id && $project->winner_designer_id && $project->status === 'in_progress')
        @php
            $escrowTransaction = $project->transaction;
            $acceptedProposal = $project->proposals->firstWhere('status', 'accepted');
            $paymentAmount = $acceptedProposal?->price ?? $project->budget;
        @endphp

        <section class="project-content-card">
            <div class="chat-workspace-info">
                <div>
                    <span class="meta-label">ESCROW PAYMENT</span>
                    <h2>Bayar Escrow Project</h2>
                    <p>
                        Designer sudah dipilih.
                        Lakukan pembayaran melalui Midtrans
                        agar dana masuk ke escrow.
                    </p>
                </div>

                <div>
                    @if($escrowTransaction?->status === 'held')
                        <span class="status-badge">
                            ✓ Pembayaran Held
                        </span>
                    @else
                        <a href="{{ route('payment.project', $project) }}"
                           class="btn primary">
                            💳
                            {{ $escrowTransaction?->status === 'pending'
                                ? 'Lanjutkan Pembayaran'
                                : 'Bayar Rp '.number_format((float)$paymentAmount, 0, ',', '.')
                            }}
                            →
                        </a>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endauth

            {{-- PROJECT COMPLETION / REVIEW --}}
            @auth
                @if(auth()->id() === $project->client_id && in_array($project->status, ['paid', 'in_progress']))
                    <section class="project-content-card">
                        <div class="chat-workspace-info">
                            <div>
                                <span class="meta-label">DELIVERY</span>
                                <h2>Project sudah dikerjakan?</h2>
                                <p>Setelah hasil diterima, tandai project selesai untuk mencatat pelepasan dana escrow ke designer.</p>
                            </div>
                            <form method="POST" action="{{ route('projects.complete', $project) }}" onsubmit="return confirm('Tandai project ini selesai dan release dana ke designer?');">
                                @csrf
                                <button type="submit" class="btn primary">✓ Selesaikan Project</button>
                            </form>
                        </div>
                    </section>
                @endif

                @if(auth()->id() === $project->client_id && $project->status === 'completed' && !$project->review)
                    <section class="project-content-card">
                        <span class="meta-label">REVIEW DESIGNER</span>
                        <h2>Beri Rating</h2>
                        <form method="POST" action="{{ route('projects.review', $project) }}">
                            @csrf
                            <select name="rating" required>
                                <option value="">Pilih rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} / 5</option>
                                @endfor
                            </select>
                            <textarea name="comment" rows="3" placeholder="Komentar (opsional)"></textarea>
                            <button type="submit" class="btn primary">Kirim Rating</button>
                        </form>
                    </section>
                @endif
            @endauth

            {{-- CHAT WORKSPACE --}}
@auth
    @if(
        $project->winner_designer_id &&
        (
            auth()->id() === $project->client_id ||
            auth()->id() === $project->winner_designer_id
        )
    )
        <section class="project-content-card chat-workspace-card">

            <div class="chat-workspace-info">
                <div>
                    <span class="meta-label">PROJECT COMMUNICATION</span>

                    <h2>💬 Chat with Designer</h2>

                    <p>
                        Komunikasikan revisi, konsep, deadline, dan kebutuhan
                        project secara langsung.
                    </p>
                </div>

                <a
                    href="{{ route('chat.show', $project) }}"
                    class="btn primary"
                >
                    Buka Chat →
                </a>
            </div>

        </section>
    @endif
@endauth

        </main>

    </div>

</div>
@endsection