<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BerangkatTimbangResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'timbang_bersih' => $this->timbang_bersih,
            'timbang_kotor' => $this->timbang_kotor,
            'nomor_nota' => $this->nomor_nota,
            'pabrik_id' => $this->pabrik_id,
            'pabrik_nama' => $this->pabrik->nama,
            'pabrik_alamat' => $this->pabrik->alamat,
            'pabrik_hp' => $this->pabrik->hp,
            'operator_id' => $this->operator->id,
            'operator_user_id' => $this->operator->user->id,
            'operator_user_name' => strtoupper($this->operator->user->name),
            'tanggal' => $this->tanggal,
            'berangkat_mobil' => $this->berangkatMobil,
            'berangkat_pabrik' => $this->berangkatPabrik,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
