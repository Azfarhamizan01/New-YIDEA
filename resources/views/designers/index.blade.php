@extends('layouts.app')

@section('content')
<section class="designers-page">

    <div class="designers-heading">
        <div class="heading-eyebrow">
            ✧ VERIFIED TALENT NETWORK
        </div>

        <h1>Top Design Specialists</h1>

        <p>
            Hire vetted creative professionals with verified portfolio
            showcases and buyer testimonials.
        </p>
    </div>

    {{-- SEARCH AND FILTER --}}
    <form
        method="GET"
        action="{{ route('designers.index') }}"
        class="designer-filter"
    >
        <div class="filter-top">

            <div class="designer-search">
                <span>⌕</span>

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search designer by name, software (Figma, Blender, Illustrator)..."
                >
            </div>

            <select name="tier">
                <option value="">All Tiers</option>
                <option value="pro" {{ request('tier') === 'pro' ? 'selected' : '' }}>
                    PRO Designers
                </option>
                <option value="standard" {{ request('tier') === 'standard' ? 'selected' : '' }}>
                    Standard Designers
                </option>
            </select>

            <select name="availability">
                <option value="">All Availability</option>
                <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>
                    Available Now
                </option>
                <option value="busy" {{ request('availability') === 'busy' ? 'selected' : '' }}>
                    Currently Busy
                </option>
            </select>

        </div>

        <div class="discipline-list">
            <button
                type="submit"
                name="specialty"
                value=""
                class="{{ !request('specialty') ? 'active' : '' }}"
            >
                All Disciplines
            </button>

            @foreach([
                'Logo & Brand Identity',
                'UI/UX Mobile & Web',
                'Packaging & Label Produk',
                'Social Media & Marketing Kit',
                'Illustration & Mascot',
                '3D & Motion Graphics'
            ] as $discipline)
                <button
                    type="submit"
                    name="specialty"
                    value="{{ $discipline }}"
                    class="{{ request('specialty') === $discipline ? 'active' : '' }}"
                >
                    {{ $discipline }}
                </button>
            @endforeach
        </div>
    </form>

    {{-- DESIGNER CARDS --}}
    <div class="designers-grid">

        @forelse($designers as $designer)
            @php
                $initials = collect(explode(' ', $designer->name))
                    ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                    ->take(2)
                    ->implode('');

                $specialty = $designer->specialty ?: 'Creative Specialist';
                $rating = number_format((float) ($designer->rating ?: 0), 2);
                $completed = $designer->completed_projects ?: 0;
            @endphp

            <article class="specialist-card">

                <div class="specialist-top">
                    <div class="specialist-profile">
                        @if($designer->avatar)
                            <img
                                src="{{ asset('storage/' . $designer->avatar) }}"
                                alt="{{ $designer->name }}"
                                class="specialist-avatar"
                            >
                        @else
                            <div class="specialist-avatar avatar-placeholder">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="specialist-info">
                            <h2>
                                {{ $designer->name }}
                                <span class="verified-icon">✓</span>
                            </h2>

                            <div class="specialist-category">
                                {{ $specialty }}
                            </div>

                            <div class="specialist-rating">
                                <span class="star">★</span>
                                <strong>{{ $rating }}</strong>
                                <span class="separator">•</span>
                                <span>{{ $completed }} Completed</span>
                            </div>
                        </div>
                    </div>

                    @if($designer->isPro ?? false)
                        <span class="pro-badge">♛ PRO</span>
                    @endif
                </div>

                <p class="specialist-description">
                    {{ $designer->bio ?: 'Creative professional with verified experience delivering high-quality design projects for modern brands and businesses.' }}
                </p>

                <div class="specialist-tags">
                    @foreach(array_slice(explode(',', $designer->skills ?? $specialty), 0, 5) as $tag)
                        <span>{{ trim($tag) }}</span>
                    @endforeach
                </div>

                <div class="specialist-footer">
                    <div class="starting-rate">
                        <small>STARTING RATE</small>
                        <strong>
                            Rp {{ number_format($designer->starting_price ?? 1500000, 0, ',', '.') }}
                            -
                            Rp {{ number_format($designer->maximum_price ?? 15000000, 0, ',', '.') }}
                        </strong>
                    </div>
<a
    href="{{ route('designers.show', $designer->id) }}"
    class="portfolio-link"
>
    View Portfolio
    <span>→</span>
</a>
                </div>

            </article>
        @empty
            <div class="designer-empty">
                <h3>No designers found</h3>
                <p>Try changing your search or filter.</p>
            </div>
        @endforelse

    </div>

    @if(method_exists($designers, 'links'))
        <div class="designer-pagination">
            {{ $designers->withQueryString()->links() }}
        </div>
    @endif

</section>
@endsection