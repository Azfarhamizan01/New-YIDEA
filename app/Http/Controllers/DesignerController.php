<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
    public function index(Request $request)
    {
        $designers = User::query()
            ->where('role', 'designer')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->where(function ($x) use ($term) {
                    $x->where('name', 'like', "%{$term}%")
                      ->orWhere('specialty', 'like', "%{$term}%")
                      ->orWhere('bio', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('rating')
            ->paginate(12)
            ->withQueryString();

        return view('designers.index', compact('designers'));
        
    }
public function show($id)
{
    $designer = User::where('role', 'designer')
        ->findOrFail($id);

    $portfolio = Project::where('winner_designer_id', $designer->id)
    ->whereIn('status', ['paid', 'completed'])
    ->latest()
    ->get();

    return view('designers.show', compact('designer', 'portfolio'));
}
}
