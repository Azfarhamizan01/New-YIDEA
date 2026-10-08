<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function index()
    {
        $current = Auth::check()
            ? Membership::where('user_id', Auth::id())->where('status', 'active')->latest()->first()
            : null;

        return view('membership', compact('current'));
    }

    public function select(Request $request, string $plan)
    {
        abort_unless(in_array($plan, ['free', 'pro', 'studio'], true), 404);
        return redirect()->route('payment.membership', ['plan' => $plan]);
    }
}
