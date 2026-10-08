<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'project_id','client_id','designer_id','amount','platform_fee',
        'type','status','external_id','membership_plan'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function designer() { return $this->belongsTo(User::class, 'designer_id'); }
}
