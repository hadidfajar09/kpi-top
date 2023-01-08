<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'absensis';

    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }
}
