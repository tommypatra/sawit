<?php

namespace App\Http\Controllers;

use App\Models\TimbangBeli;
use App\Models\TimbangNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TimbangBeliRequest;
use App\Http\Resources\TimbangBeliResource;

class TimbangBeliController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = TimbangBeli::with([
            'timbangTiket.pelanggan.user',
        ])
            ->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->WhereHas('pelanggan.user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new TimbangBeliResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => TimbangBeliResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }


    public function store(TimbangBeliRequest $request)
    {
        try {
            DB::beginTransaction();
            $nota_data_post = [
                'nama' => $request->nama,
                'operator_id' => $request->operator_id,
                'nomor_nota' => generateNomorNota($request->waktu, 'timbang'),
                'waktu' => $request->waktu,
                'jenis_bayar' => $request->jenis_bayar,
                'sumber_bayar_id' => $request->sumber_bayar_id,
                'biaya_transfer' => $request->biaya_transfer,
            ];
            $timbang_nota = TimbangNota::create($nota_data_post);

            //detail nota pada timbang beli
            $timbang_beli = [];
            foreach ($request->total_bayar as $i => $dp) {
                $beli_data_post = [
                    'timbang_nota_id' => $timbang_nota->id,
                    'jumlah_satuan' => $request->jumlah_satuan[$i],
                    'harga_satuan' => $request->harga_satuan[$i],
                    'total_bayar' => $dp,
                    'timbang_tiket_id' => $request->timbang_tiket_id[$i],
                ];
                // dd($beli_data_post);
                $timbang_beli[] = TimbangBeli::create($beli_data_post);
            }
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $timbang_nota, 'data2' => $timbang_beli], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(TimbangBeli $timbang_beli)
    {
        return response()->json(['data' => $timbang_beli], 200);
    }

    public function update(TimbangBeliRequest $request, TimbangBeli $timbang_beli)
    {
        try {
            DB::beginTransaction();
            $timbang_beli->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $timbang_beli], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(TimbangBeli $timbang_beli)
    {
        try {
            DB::beginTransaction();
            $timbang_beli->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
