<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Membership extends Model { protected $fillable=['user_id','plan','price','starts_at','ends_at','status']; protected $casts=['price'=>'decimal:2','starts_at'=>'datetime','ends_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} }
