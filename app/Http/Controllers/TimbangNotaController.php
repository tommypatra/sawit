<?php

namespace App\Http\Controllers;

use App\Models\TimbangNota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TimbangNotaRequest;
use App\Http\Resources\TimbangNotaResource;

class TimbangNotaController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = TimbangNota::with([
            'sumberBayar',
            'operator.user',
            'timbangBeli.timbangTiket.pelanggan.user',
        ])
            ->withSum('timbangBeli', 'total_bayar')
            ->withSum('timbangBeli', 'jumlah_satuan')
            ->orderBy('waktu', 'DESC')
            ->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where('jenis_bayar', $search)
                ->orWhereHas('sumberBayar', function ($query) use ($search) {
                    $query->where('nama', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('timbangBeli.timbangTiket.pelanggan.user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('operator.user', function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%');
                });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new TimbangNotaResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => TimbangNotaResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }


    public function store(TimbangNotaRequest $request)
    {
        try {
            DB::beginTransaction();
            $timbang_nota = TimbangNota::create($request);
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $timbang_nota], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $timbang_nota = TimbangNota::where('id', $id)->first();
        return response()->json(['data' => $timbang_nota], 200);
    }

    public function update(TimbangNotaRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $timbang_nota = TimbangNota::where('id', $id)->first();
            $timbang_nota->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $timbang_nota], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $timbang_nota = TimbangNota::where('id', $id)->first();
            $timbang_nota->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
