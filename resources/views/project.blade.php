@extends('layouts.app')

@section('content')
<section class="page-wrap detail">
    <a class="back" href="{{ route('projects') }}">← Kembali ke Marketplace</a>
    <div class="detail-layout">
        <article class="detail-card">
            <div class="card-top">
                <span class="category">{{ $project['category'] }}</span>
                <span class="status">{{ $project['status'] }}</span>
            </div>
            <h1>{{ $project['title'] }}</h1>
            <p class="lead">{{ $project['description'] }}</p>
            <div class="tags">@foreach($project['tags'] as $tag)<span>{{ $tag }}</span>@endforeach</div>
            <hr>
            <h3>Project Brief</h3>
            <p>Client membutuhkan solusi desain yang kuat secara visual, mudah digunakan, dan siap masuk ke tahap produksi. Designer dapat mengirimkan konsep, estimasi pengerjaan, dan proposal melalui platform.</p>
        </article>

        <aside class="side-card">
            <small>ESCROW BUDGET</small>
            <strong>{{ $project['budget'] }}</strong>
            <div class="side-line"><span>Timeline</span><b>{{ $project['timeline'] }}</b></div>
            <div class="side-line"><span>Proposals</span><b>{{ $project['proposals'] }}</b></div>
            <button class="btn primary full" onclick="alert('Demo: form proposal siap dihubungkan ke database.')">Submit Proposal →</button>
        </aside>
    </div>
</section>
@endsection
