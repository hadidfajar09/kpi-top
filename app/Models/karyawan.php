<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawans';

    protected $guarded = [];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'id');
    }
    public function kontrak()
    {
        return $this->belongsTo(kontrak::class, 'kontrak_id', 'id');
    }
    public function penempatan()
    {
        return $this->belongsTo(Penempatan::class, 'penempatan_id', 'id');
    }
}
