<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TimbangTiketResource extends JsonResource
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
        $total_bayar = $this->timbang_beli_sum_total_bayar;
        return [
            'id' => $this->id,
            'file' => $this->file,
            'file_jenis' => jenisFile($this->file),
            'timbang_bersih' => $this->timbang_bersih,
            'ram' => $this->ram,
            'tanggal' => $this->tanggal,
            'timbang_beli_total_bayar' => $total_bayar,
            'timbang_beli' => ($total_bayar > 0) ? true : false,
            'operator_id' => $this->operator->id,
            'operator_user_id' => $this->operator->user->id,
            'operator_user_name' => $this->operator->user->name,
            'pelanggan_id' => $this->pelanggan->id,
            'pelanggan_user_id' => $this->pelanggan->user->id,
            'pelanggan_user_name' => $this->pelanggan->user->name,
            'pelanggan_user_hp' => $this->pelanggan->user->hp,
            'pelanggan_user_alamat' => $this->pelanggan->user->alamat,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
