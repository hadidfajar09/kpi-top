<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DistribusiResource extends JsonResource
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
            'drop_tabung' => $this->drop_tabung,
            'status' => $this->status,
            'agent' => $this->agent->name,
            'pangkalan' => $this->pangkalan->name,
            'id_pangkalan' => $this->id_pangkalan,
            'tanggal_pengantaran' => formatTanggal($this->tanggal_pengantaran),

        ];
    }
}
