<?php

namespace App\Http\Controllers;

use App\Http\Requests\MobilRequest;
use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobilController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Mobil::orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'LIKE', '%' . $search . '%')
                ->orWhere('merk', 'LIKE', '%' . $search . '%')
                ->orWhere('no_polisi', 'LIKE', '%' . $search . '%')
                ->orWhere('warna', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new MobilResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => MobilResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(MobilRequest $request)
    {
        try {
            DB::beginTransaction();
            $mobil = Mobil::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $mobil], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Mobil $mobil)
    {
        return response()->json(['data' => $mobil], 200);
    }

    public function update(MobilRequest $request, Mobil $mobil)
    {
        try {
            DB::beginTransaction();
            $mobil->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $mobil], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Mobil $mobil)
    {
        try {
            DB::beginTransaction();
            $mobil->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
