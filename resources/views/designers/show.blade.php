@extends('layouts.app')

@section('content')

<style>
    .designer-detail {
        max-width: 1200px;
        margin: 0 auto;
        padding: 50px 24px 80px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin-bottom: 28px;
        color: #666;
        font-size: 14px;
    }

    .back-link:hover {
        color: #111;
    }

    .designer-hero {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 24px;
        padding: 36px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
        margin-bottom: 30px;
    }

    .designer-profile {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .designer-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .avatar-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #111;
        color: #fff;
        font-size: 34px;
        font-weight: 700;
    }

    .designer-name {
        margin: 0 0 8px;
        font-size: 34px;
        font-weight: 700;
    }

    .verified {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #111;
        color: #fff;
        font-size: 13px;
        vertical-align: middle;
    }

    .designer-specialty {
        margin: 0 0 14px;
        color: #666;
        font-size: 16px;
    }

    .designer-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    .star {
        color: #f5a623;
        font-size: 18px;
    }

    .designer-actions {
        display: flex;
        gap: 12px;
    }

    .btn-contact {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 22px;
        border-radius: 10px;
        background: #111;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-contact:hover {
        opacity: .9;
    }

    .designer-content {
        display: grid;
        grid-template-columns: 1fr 330px;
        gap: 30px;
    }

    .detail-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .detail-card h2 {
        margin: 0 0 20px;
        font-size: 22px;
    }

    .bio {
        color: #666;
        line-height: 1.8;
        margin: 0;
    }

    .skills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .skill {
        padding: 8px 13px;
        background: #f5f5f5;
        border-radius: 8px;
        font-size: 13px;
    }

    .portfolio-empty {
        border: 1px dashed #d8d8d8;
        border-radius: 14px;
        padding: 40px 20px;
        text-align: center;
        color: #777;
    }

    .sidebar-card {
        background: #111;
        color: #fff;
        border-radius: 20px;
        padding: 28px;
        height: fit-content;
        position: sticky;
        top: 25px;
    }

    .sidebar-card h3 {
        margin: 0 0 22px;
        font-size: 20px;
    }

    .price-label {
        display: block;
        font-size: 12px;
        opacity: .65;
        margin-bottom: 7px;
        text-transform: uppercase;
    }

    .price {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
    }

    .sidebar-info {
        border-top: 1px solid rgba(255,255,255,.15);
        padding-top: 18px;
        margin-top: 18px;
    }

    .sidebar-info div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 13px;
        font-size: 14px;
    }

    .sidebar-info span:last-child {
        opacity: .75;
    }

    @media (max-width: 800px) {
        .designer-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .designer-content {
            grid-template-columns: 1fr;
        }

        .sidebar-card {
            position: static;
        }

        .designer-profile {
            align-items: flex-start;
        }

        .designer-avatar {
            width: 90px;
            height: 90px;
        }

        .designer-name {
            font-size: 26px;
        }
    }

    .portfolio-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.portfolio-item {
    border: 1px solid #e8e8e8;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    transition: transform .2s ease, box-shadow .2s ease;
}

.portfolio-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,.08);
}

.portfolio-cover {
    height: 150px;
    background: linear-gradient(135deg, #171717, #3b3b3b);
    display: flex;
    align-items: flex-end;
    padding: 18px;
    color: #fff;
}

.portfolio-cover span {
    font-size: 12px;
    background: rgba(255,255,255,.15);
    padding: 7px 10px;
    border-radius: 6px;
}

.portfolio-body {
    padding: 20px;
}

.portfolio-category {
    font-size: 12px;
    color: #777;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.portfolio-body h3 {
    margin: 0 0 10px;
    font-size: 19px;
}

.portfolio-body p {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 18px;
}

.portfolio-footer {
    border-top: 1px solid #eee;
    padding-top: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    font-size: 12px;
}

.portfolio-footer span {
    color: #777;
}

.portfolio-footer a {
    color: #111;
    text-decoration: none;
    font-weight: 600;
}

.portfolio-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 700px) {
    .portfolio-grid {
        grid-template-columns: 1fr;
    }
}
</style>

@php
    $initials = collect(explode(' ', $designer->name))
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');

    $specialty = $designer->specialty ?: 'Creative Specialist';
    $rating = number_format((float) ($designer->rating ?: 0), 2);
    $completed = $designer->completed_projects ?: 0;
@endphp

<div class="designer-detail">

    <a href="{{ route('designers.index') }}" class="back-link">
        ← Kembali ke Designer
    </a>

    {{-- HERO --}}
    <div class="designer-hero">

        <div class="designer-profile">

            @if($designer->avatar)
                <img
                    src="{{ asset('storage/' . $designer->avatar) }}"
                    alt="{{ $designer->name }}"
                    class="designer-avatar"
                >
            @else
                <div class="designer-avatar avatar-placeholder">
                    {{ $initials }}
                </div>
            @endif

            <div>
                <h1 class="designer-name">
                    {{ $designer->name }}
                    <span class="verified">✓</span>
                </h1>

                <p class="designer-specialty">
                    {{ $specialty }}
                </p>

                <div class="designer-rating">
                    <span class="star">★</span>
                    <strong>{{ $rating }}</strong>
                    <span>•</span>
                    <span>{{ $completed }} Completed</span>
                </div>
            </div>

        </div>

        <form action="{{ route('chat.contact', $designer->id) }}" method="POST">
    @csrf
   
</form>

    </div>


    {{-- CONTENT --}}
    <div class="designer-content">

        <main>

            {{-- ABOUT --}}
            <div class="detail-card">

                <h2>Tentang Designer</h2>

                <p class="bio">
                    {{ $designer->bio ?: 'Creative professional with verified experience delivering high-quality design projects for modern brands and businesses.' }}
                </p>

            </div>


            {{-- SKILLS --}}
            <div class="detail-card">

                <h2>Keahlian</h2>

                <div class="skills">

                    @foreach(array_slice(explode(',', $designer->skills ?? $specialty), 0, 8) as $tag)

                        @if(trim($tag))
                            <span class="skill">
                                {{ trim($tag) }}
                            </span>
                        @endif

                    @endforeach

                </div>

            </div>


            {{-- PORTFOLIO --}}
            <div class="detail-card">

                <h2>Portfolio</h2>

                @if($portfolio->count())

    <div class="portfolio-grid">

        @foreach($portfolio as $project)

            <div class="portfolio-item">

                <div class="portfolio-cover">
                    <span>{{ $project->category ?? 'Design Project' }}</span>
                </div>

                <div class="portfolio-body">

                    <div class="portfolio-category">
                        {{ $project->category ?? 'Design Project' }}
                    </div>

                    <h3>
                        {{ $project->title }}
                    </h3>

                    <p>
                        {{ \Illuminate\Support\Str::limit($project->description, 120) }}
                    </p>

                    <div class="portfolio-footer">
                        <span>
                            Completed Project
                        </span>

                        <a href="{{ route('projects.show', $project) }}">
                            Lihat Project →
                        </a>
                    </div>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div class="portfolio-empty">
        <strong>Portfolio belum tersedia</strong>

        <p>
            Designer ini belum memiliki project yang telah selesai.
        </p>
    </div>

@endif

            </div>

        </main>


        {{-- SIDEBAR --}}
        <aside>

            <div class="sidebar-card">

                <h3>Informasi Designer</h3>

                <span class="price-label">
                    Starting Rate
                </span>

                <div class="price">
                    Rp {{ number_format($designer->starting_price ?? 1500000, 0, ',', '.') }}
                </div>

                <div class="sidebar-info">

                    <div>
                        <span>Specialty</span>
                        <span>{{ $specialty }}</span>
                    </div>

                    <div>
                        <span>Rating</span>
                        <span>★ {{ $rating }}</span>
                    </div>

                    <div>
                        <span>Completed</span>
                        <span>{{ $completed }}</span>
                    </div>

                   @if(isset($portfolio) && $portfolio->isNotEmpty())
<form action="{{ route('chat.contact', $portfolio->first()) }}" method="POST">
    @csrf

    <p class="contact-text">
    Siap mendiskusikan proyek Anda
</p>

<button type="submit" class="hire-btn">
    💬
    <span>Hubungi Designer</span>
</button>
</form>
@endif

                </div>

            </div>

        </aside>

    </div>

</div>

@endsection