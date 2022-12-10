<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribusi extends Model
{
    use HasFactory;

    protected $table = 'distribusis';

    protected $guarded = [];

    public function agent()
    {
        return $this->belongsTo(User::class, 'id_agent', 'id');
    }

    public function pangkalan()
    {
        return $this->belongsTo(User::class, 'id_pangkalan', 'id');
    }
}
