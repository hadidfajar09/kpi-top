<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
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
            'kode_transaksi' => $this->kode_transaksi,
            'nama_pelanggan' => $this->pelanggan->nama_pelanggan,
            'qrcode' => $this->pelanggan->qrcode,
            'nama_pangkalan' => $this->pangkalan->name,
            'HET' => $this->pangkalan->kecamatan->harga_tabung,
            'tanggal_ambil' => $this->tanggal_ambil,
            'jumlah_tabung' => $this->jumlah_tabung,
        ];
    }
}
