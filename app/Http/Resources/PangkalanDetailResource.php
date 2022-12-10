<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PangkalanDetailResource extends JsonResource
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
            'kode_user' => $this->kode_user,
            'name' => $this->name,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'stock_tabung' => $this->stock_tabung,
            'nama_kecamatan' => $this->kecamatan->nama_kecamatan,
            'nama_desa' => $this->desa->nama_desa,
           

        ];
    }
}
