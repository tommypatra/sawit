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
            'nomor_nota' => $this->nomor_nota,
            'pabrik_id' => $this->pabrik_id,
            'pabrik_nama' => $this->pabrik->nama,
            'pabrik_alamat' => $this->pabrik->alamat,
            'pabrik_hp' => $this->pabrik->hp,
            'pabrik_biaya_bongkar' => $this->pabrik->biaya_bongkar,
            'pabrik_biaya_supir' => $this->pabrik->biaya_supir,
            'pabrik_biaya_mobil' => $this->pabrik->biaya_mobil,

            'operator_id' => $this->operator->id,
            'operator_user_id' => $this->operator->user->id,
            'operator_user_name' => strtoupper($this->operator->user->name),
            'tanggal' => $this->tanggal,
            'mobil_id' => $this->mobil->id,
            'mobil_merk' => $this->mobil->merk,
            'mobil_nama' => $this->mobil->nama,
            'mobil_no_polisi' => $this->mobil->no_polisi,
            'supir_id' => $this->supir_id,
            'berangkat_supir' => $this->supir,
            'berangkat_timbang' => $this->berangkatTimbang,
            'berangkat_pabrik' => $this->berangkatPabrik,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
