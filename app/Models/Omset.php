<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Omset extends Model
{
    use HasFactory;

    protected $table = 'omsets';

    protected $guarded = [];

    // public function sales()
    // {
    //     return $this->belongsTo(karyawan::class, 'karyawan_id', 'id');
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }

    
}
