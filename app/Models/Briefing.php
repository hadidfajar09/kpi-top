<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefing extends Model
{
    use HasFactory;

    protected $table = 'briefings';

    protected $guarded = [];

    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class, 'penempatan_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
