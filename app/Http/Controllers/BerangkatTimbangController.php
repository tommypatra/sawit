<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerangkatMobil;
use App\Models\BerangkatSupir;
use App\Models\BerangkatPabrik;
use App\Models\BerangkatTimbang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BerangkatTimbangRequest;
use App\Http\Resources\BerangkatTimbangResource;

class BerangkatTimbangController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = BerangkatMobil::with([
            'pabrik',
            'mobil',
            'operator.user',
            'berangkatPabrik',
            'berangkatTimbang.ram',
            'berangkatTimbang.operator.user',
            'supir.user'
        ])
            ->withCount('berangkatPabrik')
            ->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->whereHas('pabrik', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })->orWhereHas('operator.user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('grup')) {
            $grup = $request->input('grup');
            if ($grup == 'berangkat')
                $dataQuery->having('berangkat_pabrik_count', '<', 1);
            else
                $dataQuery->having('berangkat_pabrik_count', '>', 0);
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


            // simpan 'BerangkatMobil' data
            $berangkatMobil = BerangkatMobil::create([
                'pabrik_id' => $request->pabrik_id,
                'mobil_id' => $request->mobil_id,
                'supir_id' => $request->supir_id,
                'nomor_nota' => generateNomorNota($request->tanggal, 'berangkat'),
                'tanggal' => $request->tanggal,
                'operator_id' => $request->operator_id,
            ]);

            // simpan 'BerangkatSupir' data
            // $berangkatSupir = BerangkatSupir::create([
            //     'berangkat_mobil_id' => $berangkatMobil->id,
            //     'supir_id' => $request->supir_id,
            //     'operator_id' => $request->operator_id,
            // ]);

            // Loop 'muatan' dan simpan berangkatTimbang
            foreach ($request->muatan as $muatan) {
                $berangkatMuatan = BerangkatTimbang::create([
                    'berangkat_mobil_id' => $berangkatMobil->id,
                    'ram_timbang_kotor' => $muatan['ram_timbang_kotor'],
                    'ram_timbang_bersih' => $muatan['ram_timbang_bersih'],
                    'ram_id' => $muatan['ram_id'],
                    'operator_id' => $request->operator_id,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $berangkatMobil], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = BerangkatMobil::with([
            'pabrik',
            'mobil',
            'operator.user',
            'berangkatPabrik',
            'berangkatTimbang.ram',
            'berangkatTimbang.operator.user',
            'supir.user'
        ])->where('id', $id)->first();

        if (!$dataQuery) {
            return response()->json(['message' => 'tidak ditemukan'], 404);
        }

        return response()->json(['data' => new BerangkatTimbangResource($dataQuery)], 200);
    }

    public function update(BerangkatTimbangRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $berangkatMobil = BerangkatMobil::where('id', $id)->first();

            // simpan 'berangkatMobil' data
            $berangkatMobil->update([
                'pabrik_id' => $request->pabrik_id,
                'tanggal' => $request->tanggal,
                'mobil_id' => $request->mobil_id,
                'supir_id' => $request->supir_id,
                'operator_id' => $request->operator_id,
            ]);

            // Loop 'mobil' dan simpan berdasarkan id berangkatTimbang
            foreach ($request->muatan as $muatan) {
                if ($muatan['berangkat_timbang_id'] === '') {
                    $berangkatTimbang = BerangkatTimbang::create([
                        'berangkat_mobil_id' => $id,
                        'ram_id' => $muatan['ram_id'],
                        'ram_timbang_kotor' => $muatan['ram_timbang_kotor'],
                        'ram_timbang_bersih' => $muatan['ram_timbang_bersih'],
                        'operator_id' => $request->operator_id,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $berangkatMobil], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                BerangkatTimbang::where('berangkat_mobil_id', $id)->delete();
                BerangkatPabrik::where('berangkat_mobil_id', $id)->delete();
                BerangkatMobil::where('id', $id)->delete();
            });
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage()], 500);
        }
    }

    public function hapusTimbangMuatan($id)
    {
        try {
            DB::transaction(function () use ($id) {
                BerangkatTimbang::where('id', $id)->delete();
            });
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'terjadi kesalahan saat menghapus : ' . $e->getMessage()], 500);
        }
    }
}
