@extends('layouts.app')

@section('content')
<section class="page-wrap">
    <div class="page-heading">
        <label>✧ PROJECT MARKETPLACE</label>
        <h1>Explore Active Design Briefs</h1>
        <p>Submit your creative solutions, pitch clients directly, and get paid securely with Escrow.</p>
    </div>

    <form class="filters" method="GET" action="{{ route('projects') }}">
        <div class="search">⌕ <input name="q" value="{{ request('q') }}" placeholder="Search by keywords, deliverables, tags..."></div>
        <select name="budget"><option>All Budgets</option><option>Under Rp 3 Juta</option><option>Rp 3–7 Juta</option></select>
        <select name="status"><option>All Statuses</option><option>Published</option><option>Paid</option></select>
        <select name="sort"><option>Latest First</option><option>Budget Highest</option></select>
        <button class="filter-submit">Cari</button>
    </form>

    <div class="chips">
        @foreach($categories as $category)
            <a class="{{ (request('category', 'All Categories') === $category) ? 'selected' : '' }}"
               href="{{ route('projects', ['category' => $category]) }}">{{ $category }}</a>
        @endforeach
    </div>

    <div class="project-grid marketplace">
        @forelse($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @empty
            <div class="empty">Tidak ada project yang cocok dengan pencarian.</div>
        @endforelse
    </div>
</section>
@endsection
