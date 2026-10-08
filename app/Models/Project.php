<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Project extends Model { protected $fillable=['client_id','category','title','description','budget','timeline_days','tags','status','winner_designer_id']; protected $casts=['tags'=>'array','budget'=>'decimal:2']; public function client(){return $this->belongsTo(User::class,'client_id');} public function winner(){return $this->belongsTo(User::class,'winner_designer_id');} public function proposals(){return $this->hasMany(Proposal::class);} public function messages(){return $this->hasMany(Message::class);} public function transaction(){return $this->hasOne(Transaction::class);}
public function review(){return $this->hasOne(Review::class);} public function getBudgetFormattedAttribute(){return 'Rp '.number_format((float)$this->budget,0,',','.');}}
