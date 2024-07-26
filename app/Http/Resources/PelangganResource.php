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
        // return parent::toArray($request);
        return [
            'pelanggan_id' => $this->id,
            'user_id' => $this->user_id,
            'grup_id' => $this->grup_id,
            'grup_nama' => $this->grup->nama,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'user_alamat' => $this->user->alamat,
            'user_hp' => $this->user->hp,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
