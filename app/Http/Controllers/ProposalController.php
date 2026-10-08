<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function store(Request $r, Project $project)
    {
        abort_unless(Auth::user()->isDesigner(), 403);

        abort_if($project->client_id === Auth::id(), 403);

        abort_unless(
            in_array($project->status, ['published', 'proposals_received']),
            422
        );

        $d = $r->validate([
            'price' => 'required|numeric|min:10000',
            'delivery_days' => 'required|integer|min:1|max:365',
            'cover_letter' => 'required|string|min:20|max:5000',
        ]);

        $d['project_id'] = $project->id;
        $d['designer_id'] = Auth::id();

        // Cari proposal yang sudah pernah dibuat
        $proposal = Proposal::where('project_id', $project->id)
            ->where('designer_id', Auth::id())
            ->first();

        if ($proposal) {
            // Jika sudah ada, update proposal lama
            $proposal->update($d);
        } else {
            // Jika belum ada, buat proposal baru
            Proposal::create($d);
        }

        $project->update([
            'status' => 'proposals_received'
        ]);

        return back()->with(
            'success',
            'Proposal berhasil dikirim.'
        );
    }

    public function accept(Project $project, Proposal $proposal)
    {
        abort_unless($project->client_id === Auth::id(), 403);

        abort_unless(
            $proposal->project_id === $project->id,
            404
        );

        DB::transaction(function () use ($project, $proposal) {
            $project->update([
                'winner_designer_id' => $proposal->designer_id,
                'status' => 'in_progress'
            ]);

            $proposal->update([
                'status' => 'accepted'
            ]);

            $project->proposals()
                ->whereKeyNot($proposal->id)
                ->update([
                    'status' => 'rejected'
                ]);
        });

        return back()->with(
            'success',
            'Designer terpilih. Silakan lanjutkan pembayaran escrow.'
        );
    }
}