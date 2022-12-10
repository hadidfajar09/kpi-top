<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PelangganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nama_pelanggan' => $this->nama_pelanggan,
            'kode_pelanggan' => $this->kode_pelanggan,
            'alamat' => $this->alamat,
            'jumlah_tabung' => $this->jumlah_tabung,
            'harga_tabung' => $this->harga_tabung,
            'nik' => $this->nik,
            'no_kk' => $this->no_kk,
            'qrcode' => $this->qrcode,
            'name' => $this->name,
            'nama_kecamatan' => $this->nama_kecamatan,
            'nama_desa' => $this->nama_desa,
            'nama_pekerjaan' => $this->nama_pekerjaan,
            'nominal_gaji' => formatUang($this->nominal_gaji),

        ];
    }
}
