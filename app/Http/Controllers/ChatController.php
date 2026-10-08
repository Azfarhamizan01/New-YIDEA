<?php
namespace App\Http\Controllers;
use App\Models\Project; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class ChatController extends Controller {
 public function show(Project $project){$this->authorizeProject($project);$messages=$project->messages()->with('sender')->oldest()->get();return view('chat.show',compact('project','messages'));}
 public function store(Request $r,Project $project){$this->authorizeProject($project);$d=$r->validate(['body'=>'required|string|max:3000']);$project->messages()->create(['sender_id'=>Auth::id(),'body'=>$d['body']]);return back();}
 private function authorizeProject(Project $p){$ok=$p->client_id===Auth::id()||$p->winner_designer_id===Auth::id()||Auth::user()->isAdmin();abort_unless($ok,403);}
public function contact(Project $project)
{
    $this->authorizeProject($project);

    return redirect()->route('chat.show', $project);
}
 }

