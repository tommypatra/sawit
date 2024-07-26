<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimbangBeliRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'nama' => 'required|string',
            'operator_id' => 'integer|exists:operators,id',
            'waktu' => 'required|date_format:Y-m-d H:i:s',
            'jenis_bayar' => 'required|string',
            'sumber_bayar_id' => 'required|integer|exists:sumber_bayars,id',
            'biaya_transfer' => 'required|integer',
            'pelanggan_id' => 'required|array|min:1',
            'pelanggan_id.*' => 'integer|exists:pelanggans,id',
            'timbang_tiket_id' => 'required|array|min:1',
            'timbang_tiket_id.*' => 'integer|exists:timbang_tikets,id',
            'jumlah_satuan' => 'required|array|min:1',
            'harga_satuan' => 'required|array|min:1',
            'total_bayar' => 'required|array|min:1',
        ];
        return $rules;
    }

    public function attributes()
    {
        return [
            'nama' => 'nama penerima',
            'operator_id' => 'operator',
            'waktu' => 'waktu transaksi',
            'jenis_bayar' => 'jenis pembayaran',
            'sumber_bayar_id' => 'sumber pembayaran',
            'biaya_transfer' => 'biaya transfer',
            'pelanggan_id' => 'pelanggan',
            'timbang_tiket_id' => 'tiket timbang',
            'jumlah_satuan' => 'jumlah satuan',
            'harga_satuan' => 'harga satuan',
            'total_bayar' => 'total bayar',
        ];
    }
}
