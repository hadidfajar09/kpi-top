<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cod extends Model
{
    use HasFactory;

    protected $table = 'cod';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }

}
