@extends('layouts.app')

@section('content')

{{-- HERO --}}
<section class="hero">
    <div class="eyebrow">
        ✧ Premium Design Services & Escrow Marketplace
    </div>

    <h1>
        Hire Top-Tier Creators for<br>
        Your <span>Next Vision.</span>
    </h1>

    <p>
        Post your design brief, receive tailored concept proposals from
        verified designers, select the winner, and collaborate safely
        through guaranteed escrow protection.
    </p>

    <div class="hero-buttons">
        <a class="btn primary" href="{{ route('projects.create') }}">
            ✧ Mulai Sekarang →
        </a>

        <a class="btn secondary" href="{{ route('projects.create') }}">
            ⊕ Publish Design Brief
        </a>

        <a class="btn ghost" href="{{ route('login') }}">
            Masuk Akun
        </a>
    </div>

    <div class="stats">
        <div>
            <b>100%</b>
            <small>♧ ESCROW SAFE</small>
        </div>

        <div>
            <b>4.95 ⭐</b>
            <small>CLIENT SATISFACTION</small>
        </div>

        <div>
            <b>500+</b>
            <small>VETTED CREATORS</small>
        </div>

        <div>
            <b>&lt; 24h</b>
            <small>AVERAGE PITCH TIME</small>
        </div>
    </div>
</section>


{{-- FEATURED DESIGNERS --}}
<section class="featured-designers">
    <div class="section-head">
        <div>
            <label>VERIFIED TALENT</label>

            <h2>Featured Creative Specialists</h2>

            <p>
                Industry-proven freelancers with high ratings,
                fast turnaround, and verified commercial portfolios.
            </p>
        </div>

        <a href="{{ route('designers.index') }}" class="text-link">
            Browse All Designers →
        </a>
    </div>


    <div class="designer-grid">

        {{-- DESIGNER 1 --}}
        <div class="designer-card featured-designer">
            <div class="designer-card-top">
                <div class="designer-avatar">
                    RP
                </div>

                <span class="pro-label">
                    ♛ PRO
                </span>
            </div>

            <h3>Rian Pratama</h3>

            <div class="designer-category">
                Logo & Brand Identity
            </div>

            <div class="designer-rating">
                ⭐ <strong>4.95</strong>
                <span>•</span>
                <span>52 Projects</span>
            </div>

            <p class="bio">
                Senior Brand Identity & Packaging Designer
                with 7+ years of experience creating premium
                visual identities.
            </p>

            <div class="designer-tags">
                <span>Brand Identity</span>
                <span>Logo Design</span>
                <span>Packaging</span>
            </div>

            <div class="designer-price">
                <small>Starting from</small>
                <strong>Rp 1.500.000 - Rp 15.000.000</strong>
            </div>
        </div>


        {{-- DESIGNER 2 --}}
        <div class="designer-card featured-designer highlighted">
            <div class="designer-card-top">
                <div class="designer-avatar">
                    MP
                </div>

                <span class="pro-label">
                    ♛ PRO
                </span>
            </div>

            <h3>Maya Putri Ananda</h3>

            <div class="designer-category">
                UI/UX Mobile & Web
            </div>

            <div class="designer-rating">
                ⭐ <strong>4.90</strong>
                <span>•</span>
                <span>39 Projects</span>
            </div>

            <p class="bio">
                Lead UI/UX Product Designer specialized
                in Mobile Apps, Design Systems, and
                modern digital products.
            </p>

            <div class="designer-tags">
                <span>UI/UX Design</span>
                <span>Figma</span>
                <span>Mobile App</span>
            </div>

            <div class="designer-price">
                <small>Starting from</small>
                <strong>Rp 3.000.000 - Rp 25.000.000</strong>
            </div>
        </div>


        {{-- DESIGNER 3 --}}
        <div class="designer-card featured-designer">
            <div class="designer-card-top">
                <div class="designer-avatar">
                    CK
                </div>

                <span class="pro-label">
                    ♛ PRO
                </span>
            </div>

            <h3>Citra Kirana Devi</h3>

            <div class="designer-category">
                Illustration & Mascot
            </div>

            <div class="designer-rating">
                ⭐ <strong>4.92</strong>
                <span>•</span>
                <span>31 Projects</span>
            </div>

            <p class="bio">
                Digital Illustrator & Mascot Character
                Designer for modern brands, gaming,
                and creative campaigns.
            </p>

            <div class="designer-tags">
                <span>Digital Illustration</span>
                <span>Character Mascot</span>
                <span>Storyboarding</span>
            </div>

            <div class="designer-price">
                <small>Starting from</small>
                <strong>Rp 1.000.000 - Rp 8.000.000</strong>
            </div>
        </div>


        {{-- DESIGNER 4 --}}
        <div class="designer-card featured-designer">
            <div class="designer-card-top">
                <div class="designer-avatar">
                    DW
                </div>

                <span class="pro-label">
                    ♛ PRO
                </span>
            </div>

            <h3>Dimas Wicaksono</h3>

            <div class="designer-category">
                3D & Motion Graphics
            </div>

            <div class="designer-rating">
                ⭐ <strong>4.85</strong>
                <span>•</span>
                <span>22 Projects</span>
            </div>

            <p class="bio">
                3D Artist & Motion Designer creating
                photorealistic product packaging
                renders and animations.
            </p>

            <div class="designer-tags">
                <span>3D Product Render</span>
                <span>Blender</span>
                <span>Cinema4D</span>
            </div>

            <div class="designer-price">
                <small>Starting from</small>
                <strong>Rp 2.000.000 - Rp 18.000.000</strong>
            </div>
        </div>

    </div>
</section>


{{-- OPEN PROJECTS --}}
<section class="brief-section">
    <div class="section-head">
        <div>
            <label>OPEN MARKET BRIEFS</label>

            <h2>Design Projects Ready for Concepts</h2>

            <p>
                Clients actively seeking creative pitches.
                Submit your proposal and earn through escrow.
            </p>
        </div>

        <a href="{{ route('projects.index') }}" class="text-link">
            Browse All Briefs →
        </a>
    </div>

    <div class="project-grid">

        @forelse($featured ?? [] as $project)

            @include(
                'partials.project-card',
                [
                    'project' => $project,
                    'compact' => true
                ]
            )

        @empty

            <div class="empty">
                Belum ada project.
                Login sebagai client untuk membuat project pertama.
            </div>

        @endforelse

    </div>
</section>


{{-- VISUAL SHOWCASE --}}
<section class="showcase-section">

    <div class="showcase-heading">
        <label>VISUAL SHOWCASE</label>

        <h2>
            Crafted by Creative Professionals
        </h2>

        <p>
            Explore visual work created by talented designers
            from the YIDEA marketplace.
        </p>
    </div>

    <div class="showcase-grid">

        <div class="showcase-card">
            <div class="showcase-image showcase-one">
                <span>BRAND IDENTITY</span>
            </div>

            <div class="showcase-info">
                <strong>Modern Brand Identity</strong>
                <small>Logo & Branding</small>
            </div>
        </div>

        <div class="showcase-card">
            <div class="showcase-image showcase-two">
                <span>UI/UX DESIGN</span>
            </div>

            <div class="showcase-info">
                <strong>Mobile Product Experience</strong>
                <small>UI/UX Mobile App</small>
            </div>
        </div>

        <div class="showcase-card">
            <div class="showcase-image showcase-three">
                <span>PACKAGING</span>
            </div>

            <div class="showcase-info">
                <strong>Premium Product Packaging</strong>
                <small>Packaging Design</small>
            </div>
        </div>

        <div class="showcase-card">
            <div class="showcase-image showcase-four">
                <span>3D MOTION</span>
            </div>

            <div class="showcase-info">
                <strong>Photorealistic 3D Render</strong>
                <small>3D & Motion Graphics</small>
            </div>
        </div>

    </div>

</section>


{{-- PRO CTA --}}
<section class="dark-cta">

    <div class="cta-grid">

        <div>

            <div class="eyebrow gold">
                ♕ YIDEA PRO Subscription
            </div>

            <h2>
                Scale Your Freelance Design
                <br>
                Career with PRO Status
            </h2>

            <p>
                Receive immediate push alerts for high-value client
                briefs, display a prominent golden verified badge,
                benefit from a lower platform commission,
                and enjoy unlimited proposals.
            </p>

            <div class="pro-benefits">
                <span>✓ Instant Project Alerts</span>
                <span>✓ Verified PRO Badge</span>
                <span>✓ 5% Platform Commission</span>
                <span>✓ Unlimited Proposal Capacity</span>
            </div>

            <a
                href="{{ route('membership') }}"
                class="btn pro-start"
            >
                Start PRO Creator Membership
            </a>

        </div>


        <div class="mini-plan">

            <span>Top Value</span>

            <h3>
                PRO Creator Plan
            </h3>

            <p>
                Most popular for full-time & studio freelancers
            </p>

            <strong>
                Rp 49.000
                <small>/ month</small>
            </strong>

            <a
                href="{{ route('membership') }}"
                class="btn primary full"
            >
                View Subscription Plans
            </a>

        </div>

    </div>

</section>

@endsection