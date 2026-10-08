<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Models\Project;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

Route::get('/', function(){ $featured=Project::with('client')->withCount('proposals')->latest()->take(3)->get(); return view('home',compact('featured')); })->name('home');
Route::get('/projects', [ProjectController::class,'index'])->name('projects.index');
Route::get('/designers', [DesignerController::class,'index'])->name('designers.index');
Route::get('/designers/{id}', [DesignerController::class, 'show'])
    ->name('designers.show');
Route::get('/membership', [MembershipController::class,'index'])->name('membership');

Route::middleware('guest')->group(function(){
 Route::get('/login',[AuthController::class,'showLogin'])->name('login');
 Route::post('/login',[AuthController::class,'login'])->name('login.store');
 Route::get('/register',[AuthController::class,'showRegister'])->name('register');
 Route::post('/register',[AuthController::class,'register'])->name('register.store');
});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::post('/payments/midtrans/callback',[PaymentController::class,'callback'])->name('payment.callback');

Route::middleware('auth')->group(function(){
 Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
 Route::get('/workspace',[DashboardController::class,'index'])->name('workspace');
 Route::post('/membership/{plan}',[MembershipController::class,'select'])->name('membership.select');
 Route::get('/payments/project/{project}',[PaymentController::class,'checkoutProject'])->name('payment.project');
 Route::get('/payments/membership/{plan}',[PaymentController::class,'checkoutMembership'])->name('payment.membership');
 Route::post('/projects/{project}/complete',[PaymentController::class,'completeProject'])->middleware('role:client,admin')->name('projects.complete');
 Route::post('/projects/{project}/review',[ReviewController::class,'store'])->middleware('role:client')->name('projects.review');
 Route::post('/projects/{project}/contact', [ChatController::class, 'contact'])
    ->name('chat.contact');
 Route::get('/projects/create',[ProjectController::class,'create'])->middleware('role:client,admin')->name('projects.create');
 Route::post('/projects',[ProjectController::class,'store'])->middleware('role:client,admin')->name('projects.store');
 Route::get('/projects/{project}/edit',[ProjectController::class,'edit'])->middleware('role:client,admin')->name('projects.edit');
 Route::put('/projects/{project}',[ProjectController::class,'update'])->middleware('role:client,admin')->name('projects.update');
 Route::delete('/projects/{project}',[ProjectController::class,'destroy'])->middleware('role:client,admin')->name('projects.destroy');
 Route::post('/projects/{project}/proposals',[ProposalController::class,'store'])->middleware('role:designer')->name('proposals.store');
 Route::post('/projects/{project}/proposals/{proposal}/accept',[ProposalController::class,'accept'])->middleware('role:client,admin')->name('proposals.accept');
 Route::get('/projects/{project}/chat',[ChatController::class,'show'])->name('chat.show');
 Route::post('/projects/{project}/chat',[ChatController::class,'store'])->name('chat.store');
 Route::get('/admin',[AdminController::class,'index'])->middleware('role:admin')->name('admin.index');
 Route::get('/payments/{transaction}/check-status', 
    [PaymentController::class, 'checkStatus']
    
)->name('payments.check-status');
});
Route::get('/projects/{project}', [ProjectController::class,'show'])->name('projects.show');

