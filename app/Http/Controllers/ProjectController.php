<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // <-- Tambahkan baris ini
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $r)
{
    $q = Project::with('client');

    // Pencarian keyword
    if ($r->filled('q')) {
        $term = $r->q;

        $q->where(function ($x) use ($term) {
            $x->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhere('category', 'like', "%{$term}%")
                ->orWhere('tags', 'like', "%{$term}%");
        });
    }

    // Filter kategori
    if (
        $r->filled('category') &&
        $r->category !== 'All Categories'
    ) {
        $q->where('category', $r->category);
    }

    // Filter status
    if (
        $r->filled('status') &&
        $r->status !== 'All Statuses'
    ) {
        $q->where('status', $r->status);
    }

    // Sorting
    if ($r->sort === 'Oldest First') {
        $q->oldest();
    } else {
        $q->latest();
    }

    // Pagination
    $projects = $q->paginate(9)->withQueryString();

    return view('projects.index', compact('projects'));
}

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'category' => 'required|string|max:80',
            'title' => 'required|string|max:180',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:10000',
            'timeline_days' => 'required|integer|min:1|max:365',
            'tags' => 'nullable|string|max:500'
        ]);

        $d['client_id'] = Auth::id();
        $d['tags'] = collect(explode(',', $d['tags'] ?? ''))
            ->map(fn($x) => trim($x))
            ->filter()
            ->values()
            ->all();

        $p = Project::create($d);

        return redirect()->route('projects.show', $p)->with('success', 'Project berhasil dipublikasikan.');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'proposals.designer', 'winner']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        abort_unless($project->client_id === Auth::id() || Auth::user()->isAdmin(), 403);
        return view('projects.edit', compact('project'));
    }

    public function update(Request $r, Project $project)
    {
        abort_unless($project->client_id === Auth::id() || Auth::user()->isAdmin(), 403);

        $d = $r->validate([
            'category' => 'required|string|max:80',
            'title' => 'required|string|max:180',
            'description' => 'required|string',
            'budget' => 'required|numeric|min:10000',
            'timeline_days' => 'required|integer|min:1|max:365',
            'tags' => 'nullable|string|max:500'
        ]);

        $d['tags'] = collect(explode(',', $d['tags'] ?? ''))
            ->map(fn($x) => trim($x))
            ->filter()
            ->values()
            ->all();

        $project->update($d);

        return redirect()->route('projects.show', $project)->with('success', 'Project diperbarui.');
    }

    public function destroy(Project $project)
    {
        abort_unless($project->client_id === Auth::id() || Auth::user()->isAdmin(), 403);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project dihapus.');
    }
}