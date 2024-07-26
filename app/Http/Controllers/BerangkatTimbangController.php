<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerangkatMobil;
use App\Models\BerangkatSupir;
use App\Models\BerangkatTimbang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BerangkatTimbangRequest;
use App\Http\Resources\BerangkatTimbangResource;

class BerangkatTimbangController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = BerangkatTimbang::with(['operator.user', 'berangkatPabrik', 'pabrik', 'berangkatMobil.mobil', 'berangkatMobil.berangkatSupir.supir.user'])->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->whereHas('pabrik', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })->orWhereHas('operator.user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new BerangkatTimbangResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => BerangkatTimbangResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(BerangkatTimbangRequest $request)
    {
        // return response()->json($request);

        try {
            DB::beginTransaction();

            // simpan 'BerangkatTimbang' data
            $berangkatTimbang = BerangkatTimbang::create([
                'pabrik_id' => $request->pabrik_id,
                'nomor_nota' => generateNomorNota($request->tanggal, 'berangkat'),
                'tanggal' => $request->tanggal,
                'timbang_bersih' => $request->timbang_bersih,
                'timbang_kotor' => $request->timbang_kotor,
                'operator_id' => $request->operator_id,
            ]);

            // Loop 'mobil' dan simpan berdasarkan id berangkatTimbang
            foreach ($request->mobil as $mobil) {
                $berangkatMobil = BerangkatMobil::create([
                    'berangkat_timbang_id' => $berangkatTimbang->id,
                    'mobil_id' => $mobil['mobil_id'],
                    'operator_id' => $request->operator_id,
                ]);

                foreach ($mobil['supir_list'] as $supir) {
                    $berangkatSupir = BerangkatSupir::create([
                        'berangkat_mobil_id' => $berangkatMobil->id,
                        'supir_id' => $supir['supir_id'],
                        'operator_id' => $request->operator_id,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $berangkatTimbang], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = BerangkatTimbang::with(['operator.user', 'berangkatPabrik', 'pabrik', 'berangkatMobil.mobil', 'berangkatMobil.berangkatSupir.supir.user'])
            ->where('id', $id)
            ->first();

        if (!$dataQuery) {
            return response()->json(['message' => 'tidak ditemukan'], 404);
        }

        return response()->json(['data' => new BerangkatTimbangResource($dataQuery)], 200);
    }

    public function update(BerangkatTimbangRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $berangkatTimbang = BerangkatTimbang::where('id', $id)->first();

            // simpan 'BerangkatTimbang' data
            $berangkatTimbang->update([
                'pabrik_id' => $request->pabrik_id,
                'tanggal' => $request->tanggal,
                'timbang_bersih' => $request->timbang_bersih,
                'timbang_kotor' => $request->timbang_kotor,
                'operator_id' => $request->operator_id,
            ]);

            // Loop 'mobil' dan simpan berdasarkan id berangkatTimbang
            foreach ($request->mobil as $mobil) {
                if ($mobil['berangkat_mobil_id'] === '') {
                    $berangkatMobil = BerangkatMobil::create([
                        'berangkat_timbang_id' => $id,
                        'mobil_id' => $mobil['mobil_id'],
                        'operator_id' => $request->operator_id,
                    ]);
                    $mobil['berangkat_mobil_id'] = $berangkatMobil->id;
                }

                foreach ($mobil['supir_list'] as $supir) {
                    if ($supir['berangkat_supir_id'] === '')
                        $berangkatSupir = BerangkatSupir::create([
                            'berangkat_mobil_id' => $mobil['berangkat_mobil_id'],
                            'supir_id' => $supir['supir_id'],
                            'operator_id' => $request->operator_id,
                        ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $berangkatTimbang], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $berangkatTimbang = BerangkatTimbang::where('id', $id)->first();
            $berangkatTimbang->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
