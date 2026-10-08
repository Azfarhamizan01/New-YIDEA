<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['project_id', 'client_id', 'designer_id', 'rating', 'comment'];

    public function project() { return $this->belongsTo(Project::class); }
    public function client() { return $this->belongsTo(User::class, 'client_id'); }
    public function designer() { return $this->belongsTo(User::class, 'designer_id'); }
}
