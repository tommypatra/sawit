<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimbangNotaResource extends JsonResource
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
            'waktu' => $this->waktu,
            'nama' => strtoupper($this->nama),
            'sumber_bayar_id' => $this->sumberBayar->id,
            'sumber_bayar_nama' => $this->sumberBayar->nama,
            'jenis_bayar' => $this->jenis_bayar,
            'nomor_nota' => $this->nomor_nota,
            'biaya_transfer' => $this->biaya_transfer,
            'timbang_beli_total_bayar' => $this->timbang_beli_sum_total_bayar,
            'timbang_beli_total_berat' => $this->timbang_beli_sum_jumlah_satuan,
            'operator_id' => $this->operator->id,
            'operator_user_id' => $this->operator->user->id,
            'operator_user_name' => strtoupper($this->operator->user->name),
            'detail' => $this->timbangBeli,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
