<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimbangTiketRequest;
use App\Http\Resources\TimbangTiketResource;
use App\Models\TimbangTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimbangTiketController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = TimbangTiket::with([
            'operator.user',
            'pelanggan.user',
        ])
            ->withSum('timbangBeli', 'total_bayar')
            ->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC');

        if ($request->filled('masuk')) {
            $masuk = $request->input('masuk');
            if ($masuk == 1)
                $dataQuery->doesntHave('timbangBeli');
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where(function ($query) use ($search) {
                $query->where('timbang_bersih', $search)
                    ->orWhereHas('operator.user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('pelanggan.user', function ($query) use ($search) {
                        $query->where('name', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new TimbangTiketResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => TimbangTiketResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }


    public function store(TimbangTiketRequest $request)
    {
        try {
            if ($request->hasFile('file_upload')) {
                $storagePath = 'timbang_tiket/' . date('Y') . '/' . date('m');
                $uploadProses = uploadFile($request, 'file_upload', $storagePath);
                if ($uploadProses) {
                    $request['file'] = $uploadProses['path'];
                } else {
                    return response()->json(['message' => $uploadProses], 500);
                }
            }
            // dd($request->all());
            DB::beginTransaction();
            $timbang_tiket = TimbangTiket::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $timbang_tiket], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(TimbangTiket $timbang_tiket)
    {
        return response()->json(['data' => $timbang_tiket], 200);
    }

    public function update(TimbangTiketRequest $request, TimbangTiket $timbang_tiket)
    {
        try {
            DB::beginTransaction();
            if ($request->hasFile('file_upload')) {
                $storagePath = 'timbang_tiket/' . date('Y') . '/' . date('m');
                $uploadProses = uploadFile($request, 'file_upload', $storagePath);
                if ($uploadProses) {
                    $request['file'] = $uploadProses['path'];
                    //hapus file upload lama
                    hapusFile($timbang_tiket, 'file');
                } else {
                    return response()->json(['message' => $uploadProses], 500);
                }
            }

            $timbang_tiket->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $timbang_tiket], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(TimbangTiket $timbang_tiket)
    {
        try {
            DB::beginTransaction();
            hapusFile($timbang_tiket, 'file');
            $timbang_tiket->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
