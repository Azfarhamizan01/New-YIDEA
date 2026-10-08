<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'YIDEA PRO' }}</title>

    <link rel="stylesheet" href="{{ asset('css/yidea.css') }}">
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">

        {{-- LOGO --}}
        <a href="{{ route('home') }}" class="brand-logo">
            <span class="brand-icon">Y</span>

            <span class="brand-text">
                <strong>YIDEA</strong>
                <small>PRO</small>
            </span>
        </a>

        {{-- NAVIGATION --}}
        <nav class="main-nav">

            <a href="{{ route('projects.index') }}">
                Browse<br>
                Projects
            </a>

            <a
                href="{{ route('designers.index') }}"
                class="{{ request()->routeIs('designers.*') ? 'active' : '' }}"
            >
                Designers
            </a>

            @auth
    @if(auth()->user()->role === 'designer')
        <a href="{{ route('dashboard') }}">
            My<br>
            Workspace
        </a>
    @elseif(auth()->user()->role === 'client')
        <a href="{{ route('dashboard') }}">
            My<br>
            Projects
        </a>
    @elseif(auth()->user()->role === 'admin')
        <a href="{{ route('dashboard') }}">
            Admin<br>
            Workspace
        </a>
    @endif
@else
    <a href="{{ route('login') }}">
        My<br>
        Workspace
    </a>
@endauth

            <a href="{{ route('membership') }}" class="membership-link">
                <span>♕</span>
                <span>
                    PRO<br>
                    Membership
                </span>
            </a>

        </nav>

        {{-- OWNER HQ --}}
       @auth
    @if(auth()->user()->role === 'designer')
        <a href="{{ route('dashboard') }}" class="owner-hq-button">
            <span class="owner-status"></span>

            <span>
                Owner<br>
                HQ
            </span>
        </a>
    @elseif(auth()->user()->role === 'client')
        <a href="{{ route('dashboard') }}" class="owner-hq-button">
            <span class="owner-status"></span>

            <span>
                Client<br>
                HQ
            </span>
        </a>
    @endif
@else
    <a href="{{ route('login') }}" class="owner-hq-button">
        <span class="owner-status"></span>

        <span>
            Owner<br>
            HQ
        </span>
    </a>
@endauth

        {{-- SEARCH --}}
<div style="position:relative;">
    <button type="button" class="nav-search" id="searchToggle">
        ⌕
    </button>

    <form
        id="navSearchForm"
        method="GET"
        action="{{ route('projects.index') }}"
        style="
            display:none;
            position:absolute;
            top:55px;
            right:0;
            width:300px;
            background:white;
            padding:10px;
            border-radius:12px;
            box-shadow:0 8px 25px rgba(0,0,0,.15);
            z-index:9999;
        "
    >
        <input
            type="text"
            name="q"
            placeholder="Cari project..."
            autocomplete="off"
            style="
                width:100%;
                padding:11px 13px;
                border:1px solid #ddd;
                border-radius:8px;
                outline:none;
                box-sizing:border-box;
            "
        >

        <button
            type="submit"
            style="
                width:100%;
                margin-top:8px;
                padding:10px;
                border:0;
                border-radius:8px;
                background:#5b50e6;
                color:white;
                cursor:pointer;
                font-weight:600;
            "
        >
            Cari Project
        </button>
    </form>
</div>

       {{-- MOBILE VIEW --}}
<button type="button" id="mobileViewButton" class="mobile-view-btn">
    ▯
    <span>Mobile<br>View</span>
</button>

        {{-- ROLE --}}
        @auth
    <div class="role-selector">
        @if(auth()->user()->role === 'client')
            <span class="role-icon">♙</span>
            <strong>Client</strong>
        @elseif(auth()->user()->role === 'designer')
            <span class="role-icon">♙</span>
            <strong>Designer</strong>
        @elseif(auth()->user()->role === 'admin')
            <span class="role-icon">♙</span>
            <strong>Admin</strong>
        @else
            <span class="role-icon">♙</span>
            <strong>{{ ucfirst(auth()->user()->role) }}</strong>
        @endif

        <span class="role-arrow">⌄</span>
    </div>
@else
    <div class="role-selector">
        <span class="role-icon">♙</span>
        <strong>Guest</strong>
        <span class="role-arrow">⌄</span>
    </div>
@endauth

        {{-- CREDITS --}}
        <div class="credits-box">
            <span>●</span>

            <strong>
                520<br>
                CREDITS
            </strong>
        </div>

        {{-- NOTIFICATION --}}
        <div class="notification-icon">
            ♧
            <span>1</span>
        </div>

        {{-- ACCOUNT --}}
        @auth
            <div class="account-box">
                <div class="account-name">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>
                        {{ ucfirst(auth()->user()->role) }}<br>
                        Account
                    </small>
                </div>

                @if(auth()->user()->avatar)
                    <img
                        src="{{ asset('storage/' . auth()->user()->avatar) }}"
                        alt="{{ auth()->user()->name }}"
                    >
                @else
                    <div class="account-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-button">
                    Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="login-link">
                Masuk
            </a>
        @endauth

    </div>
</header>

@if(request()->routeIs('home'))
    <div class="announcement-bar">
        <div class="announcement-inner">
            <span class="announcement-text">
                Marketplace Desain Profesional No. 1 di Indonesia
            </span>

            <a href="{{ route('projects.index') }}" class="announcement-button">
                Jelajahi Project
                <span>→</span>
            </a>
        </div>
    </div>
@endif


@if(session('success'))
    <div class="toast">
        {{ session('success') }}
        <button onclick="this.parentElement.remove()">×</button>
    </div>
@endif

@if($errors->any())
    <div class="toast error">
        {{ $errors->first() }}
        <button onclick="this.parentElement.remove()">×</button>
    </div>
@endif

<main>
    @yield('content')
</main>

<script src="{{ asset('js/yidea.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const button = document.getElementById('mobileViewButton');

    // Ambil status Mobile View yang sebelumnya disimpan
    const mobileMode = localStorage.getItem('yidea_mobile_view');

    // Jika sebelumnya Mobile View aktif
    if (mobileMode === 'true') {
        document.body.classList.add('mobile-preview');

        if (button) {
            button.classList.add('active');

            button.innerHTML =
                '▣ <span>Desktop<br>View</span>';
        }
    }

    // Tombol Mobile View
    if (button) {

        button.addEventListener('click', function () {

            const active =
                document.body.classList.toggle('mobile-preview');

            // Simpan status
            localStorage.setItem(
                'yidea_mobile_view',
                active ? 'true' : 'false'
            );

            // Ubah tampilan tombol
            button.classList.toggle('active', active);

            button.innerHTML = active
                ? '▣ <span>Desktop<br>View</span>'
                : '▯ <span>Mobile<br>View</span>';
        });

    }

});
// SEARCH NAVBAR
const searchToggle = document.getElementById('searchToggle');
const navSearchForm = document.getElementById('navSearchForm');

if (searchToggle && navSearchForm) {
    searchToggle.addEventListener('click', function (event) {
        event.stopPropagation();

        navSearchForm.style.display =
            navSearchForm.style.display === 'none'
                ? 'block'
                : 'none';
    });

    document.addEventListener('click', function (event) {
        if (
            !navSearchForm.contains(event.target) &&
            !searchToggle.contains(event.target)
        ) {
            navSearchForm.style.display = 'none';
        }
    });
}
</script>

</body>
</html>