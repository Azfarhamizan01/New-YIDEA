<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_unless($project->client_id === Auth::id(), 403);
        abort_unless($project->status === 'completed' && $project->winner_designer_id, 422, 'Project belum selesai.');
        abort_if(Review::where('project_id', $project->id)->exists(), 422, 'Review untuk project ini sudah diberikan.');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($project, $data) {
            Review::create([
                'project_id' => $project->id,
                'client_id' => Auth::id(),
                'designer_id' => $project->winner_designer_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);

            $designer = $project->winner;
            $reviewCount = Review::where('designer_id', $designer->id)->count();
            $ratingSum = Review::where('designer_id', $designer->id)->sum('rating');
            $designer->update([
                'rating' => $reviewCount ? round($ratingSum / $reviewCount, 2) : 0,
            ]);
        });

        return back()->with('success', 'Rating berhasil disimpan.');
    }
}
