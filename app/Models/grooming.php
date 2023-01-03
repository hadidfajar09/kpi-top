<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grooming extends Model
{
    use HasFactory;

    protected $table = 'groomings';

    protected $guarded = [];

    // public function karyawan()
    // {
    //     return $this->belongsTo(karyawan::class, 'karyawan_id', 'id');
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }
}
