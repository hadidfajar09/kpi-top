<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\ToModel;

class PelangganImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pelanggan([
              'nama_pelanggan' => $row[1],
              'kode_pelanggan' => $row[2],
              'alamat' => $row[3],
              'jumlah_tabung' => $row[4],
              'jatah_bulanan' => $row[5],
              'nik' => $row[6],
              'no_kk' => $row[7],
              'qrcode' => $row[8],
              'id_pangkalan' => $row[9],
              'id_pekerjaan' => $row[10],
              'id_penghasilan' => $row[11],
              'id_kecamatan' => $row[12],
              'id_desa' => $row[13],

        ]);
    }
}
