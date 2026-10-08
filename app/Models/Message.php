<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Message extends Model { protected $fillable=['project_id','sender_id','body','read_at']; protected $casts=['read_at'=>'datetime']; public function sender(){return $this->belongsTo(User::class,'sender_id');} public function project(){return $this->belongsTo(Project::class);}}
