@extends('layouts.app')
@section('content')<section class="page-wrap"><div class="page-heading"><label>✧ PROJECT MARKETPLACE</label><h1>Explore Active Design Briefs</h1><p>Submit your creative solutions, pitch clients directly, and get paid securely with Escrow.</p></div><form class="filters" method="GET"><div class="search">⌕ <input name="q" value="{{ request('q') }}" placeholder="Search by keywords, deliverables, tags..."></div><select name="category"><option>All Categories</option>@foreach(['Logo & Brand Identity','UI/UX Mobile & Web','Packaging & Label Produk','Social Media & Marketing Kit','Illustration & Mascot','3D & Motion Graphics'] as $c)<option @selected(request('category')===$c)>{{ $c }}</option>@endforeach</select><select name="status"><option>All Statuses</option><option value="published" @selected(request('status')==='published')>Published</option><option value="proposals_received" @selected(request('status')==='proposals_received')>Proposals Received</option><option value="paid" @selected(request('status')==='paid')>Paid</option></select><select name="sort"><option value="Latest First"
        @selected(request('sort', 'Latest First') === 'Latest First')>
        Latest First
    </option>

    <option value="Oldest First"
        @selected(request('sort') === 'Oldest First')>
        Oldest First
    </option></select><button class="filter-submit">Cari</button></form><div class="project-grid marketplace">@forelse($projects as $project) @include('partials.project-card',['project'=>$project]) @empty<div class="empty">Tidak ada project yang cocok.</div>@endforelse</div><div class="pagination">{{ $projects->links() }}</div></section>@endsection
