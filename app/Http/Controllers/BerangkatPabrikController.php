<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerangkatMobil;
use App\Models\BerangkatSupir;
use App\Models\BerangkatPabrik;
use App\Models\BerangkatTimbang;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BerangkatPabrikRequest;
use App\Http\Resources\BerangkatPabrikResource;
use App\Http\Requests\UpdateTimbangPabrikRequest;

class BerangkatPabrikController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = BerangkatPabrik::with(['operator.user', 'berangkatTimbang.pabrik'])->orderBy('id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->whereHas('berangkatTimbang.pabrik', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })->orWhereHas('operator.user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new BerangkatPabrikResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => BerangkatPabrikResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(BerangkatPabrikRequest $request)
    {
        // return response()->json($request);

        try {
            DB::beginTransaction();

            $nilai_susut_total = 0;
            if ($request->tp < 1) {
                $nilai_susut_total = ($request->pabrik_timbang_kotor - $request->ram_berat_kotor_awal);
            } else {
                $nilai_susut_total = ($request->ram_berat_kotor_awal - $request->pabrik_timbang_kotor);
            }
            $request['nilai_susut'] = $nilai_susut_total;
            $berangkatPabrik = BerangkatPabrik::create($request->all());


            foreach ($request->muatan as $muatan) {
                $berangkatPabrik = BerangkatTimbang::where('id', $muatan['berangkat_timbang_id'])->first();

                $nilai_susut = 0;
                // dd($berangkatPabrik);
                if ($muatan['persen'] < 1) {
                    $nilai_susut = $muatan['pabrik_timbang_kotor'] - $berangkatPabrik->ram_timbang_kotor;
                } else {
                    $nilai_susut = $berangkatPabrik->ram_timbang_kotor - $muatan['pabrik_timbang_kotor'];
                }

                $updateBerangkatPabrik = [
                    'persen' => $muatan['persen'],
                    'pabrik_timbang_kotor' => $muatan['pabrik_timbang_kotor'],
                    'operator_id' => $request->operator_id,
                    'nilai_susut' => $nilai_susut,
                    'harga' => $muatan['harga'],
                ];
                // dd($updateBerangkatPabrik);
                $berangkatPabrik->update($updateBerangkatPabrik);
            }

            DB::commit();

            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $berangkatPabrik], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = BerangkatPabrik::where('id', $id)->first();
        if (!$dataQuery) {
            return response()->json(['message' => 'tidak ditemukan'], 404);
        }
        return response()->json(['data' => new BerangkatPabrikResource($dataQuery)], 200);
    }

    public function update(BerangkatPabrikRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $berangkatPabrik = BerangkatPabrik::where('id', $id)->first();

            $nilai_susut_total = 0;
            if ($request->tp < 1) {
                $nilai_susut_total = ($request->pabrik_timbang_kotor - $request->ram_berat_kotor_awal);
            } else {
                $nilai_susut_total = ($request->ram_berat_kotor_awal - $request->pabrik_timbang_kotor);
            }
            $request['nilai_susut'] = $nilai_susut_total;

            $berangkatPabrik->update($request->all());


            foreach ($request->muatan as $muatan) {
                $berangkatPabrik = BerangkatTimbang::where('id', $muatan['berangkat_timbang_id'])->first();

                $nilai_susut = 0;
                // dd($berangkatPabrik);
                if ($muatan['persen'] < 1) {
                    $nilai_susut = $muatan['pabrik_timbang_kotor'] - $berangkatPabrik->ram_timbang_kotor;
                } else {
                    $nilai_susut = $berangkatPabrik->ram_timbang_kotor - $muatan['pabrik_timbang_kotor'];
                }

                $updateBerangkatPabrik = [
                    'persen' => $muatan['persen'],
                    'pabrik_timbang_kotor' => $muatan['pabrik_timbang_kotor'],
                    'operator_id' => $request->operator_id,
                    'nilai_susut' => $nilai_susut,
                    'harga' => $muatan['harga'],
                ];
                $berangkatPabrik->update($updateBerangkatPabrik);
            }

            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $berangkatPabrik], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $berangkatPabrik = BerangkatPabrik::where('id', $id)->first();
            $berangkatPabrik->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function hapusTimbangPabrikMobil($id)
    {
        try {
            DB::beginTransaction();
            //update berangkat timbang
            BerangkatTimbang::where('berangkat_mobil_id', $id)->update([
                'pabrik_timbang_kotor' => null,
                'harga' => null,
                'nilai_susut' => null,
                'persen' => null,
            ]);

            //hapus berangkat pabrik
            BerangkatPabrik::where('berangkat_mobil_id', $id)->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    function updateTimbangPabrik(UpdateTimbangPabrikRequest $request)
    {
        try {
            DB::beginTransaction();
            $berangkatPabrik = BerangkatPabrik::create($request->all());

            DB::commit();

            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $berangkatPabrik], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }
}
