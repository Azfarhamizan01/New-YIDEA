<?php
namespace App\Http\Controllers;
use App\Models\User; use App\Models\Membership; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use Illuminate\Support\Facades\Hash; use Illuminate\Validation\Rules\Password;
class AuthController extends Controller {
 public function showLogin(){return view('auth.login');}
 public function login(Request $r){$data=$r->validate(['email'=>'required|email','password'=>'required']); if(Auth::attempt($data,$r->boolean('remember'))){$r->session()->regenerate(); return redirect()->intended(route('dashboard'));} return back()->withErrors(['email'=>'Email atau password salah.'])->withInput();}
 public function showRegister(){return view('auth.register');}
 public function register(Request $r){$data=$r->validate(['name'=>'required|string|max:100','email'=>'required|email|max:150|unique:users,email','password'=>['required','confirmed',Password::min(8)],'role'=>'required|in:client,designer']); $user=User::create($data); Membership::create(['user_id'=>$user->id,'plan'=>'free','price'=>0,'starts_at'=>now(),'status'=>'active']); Auth::login($user); $r->session()->regenerate(); return redirect()->route('dashboard')->with('success','Akun berhasil dibuat.');}
 public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('home');}
}
