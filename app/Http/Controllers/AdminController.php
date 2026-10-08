<?php
namespace App\Http\Controllers;
use App\Models\User; use App\Models\Project; use App\Models\Proposal; use App\Models\Transaction;
class AdminController extends Controller { public function index(){return view('admin.index',['users'=>User::count(),'projects'=>Project::count(),'proposals'=>Proposal::count(),'transactions'=>Transaction::sum('amount')]);} }
