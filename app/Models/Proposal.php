<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Proposal extends Model { protected $fillable=['project_id','designer_id','price','delivery_days','cover_letter','status']; protected $casts=['price'=>'decimal:2']; public function project(){return $this->belongsTo(Project::class);} public function designer(){return $this->belongsTo(User::class,'designer_id');} public function getPriceFormattedAttribute(){return 'Rp '.number_format((float)$this->price,0,',','.');}}
