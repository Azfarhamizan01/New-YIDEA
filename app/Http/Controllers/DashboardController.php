<?php
namespace App\Http\Controllers;
use App\Models\Project; use App\Models\Proposal; use App\Models\Membership; use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller { public function index(){ $u=Auth::user(); $projects=$u->isClient()?Project::where('client_id',$u->id)->latest()->get():Project::where('winner_designer_id',$u->id)->latest()->get(); $proposals=$u->isDesigner()?Proposal::where('designer_id',$u->id)->with('project')->latest()->get():collect(); $membership=Membership::where('user_id',$u->id)->where('status','active')->latest()->first(); return view('dashboard',compact('projects','proposals','membership')); } }
