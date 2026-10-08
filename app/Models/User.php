<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable; use Illuminate\Notifications\Notifiable; use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Authenticatable { use HasFactory,Notifiable; protected $fillable=['name','email','password','role','avatar','bio','specialty','balance']; protected $hidden=['password','remember_token']; protected function casts():array{return ['email_verified_at'=>'datetime','password'=>'hashed','rating'=>'decimal:2','balance' => 'decimal:2',];} public function projects(){return $this->hasMany(Project::class,'client_id');} public function proposals(){return $this->hasMany(Proposal::class,'designer_id');} public function memberships(){return $this->hasMany(Membership::class);}
public function reviewsReceived(){return $this->hasMany(Review::class,'designer_id');}
public function reviewsGiven(){return $this->hasMany(Review::class,'client_id');} public function isDesigner(){return $this->role==='designer';} public function isClient(){return $this->role==='client';} public function isAdmin(){return $this->role==='admin';}}
