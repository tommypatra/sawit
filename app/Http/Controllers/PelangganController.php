<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelangganRequest;
use App\Http\Resources\PelangganResource;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Pelanggan::with([
            'user' => function ($query) {
                $query->orderBy('name', 'asc');
            },
            'grup'
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new PelangganResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => PelangganResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(PelangganRequest $request)
    {
        try {
            DB::beginTransaction();
            $pelanggan = Pelanggan::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $pelanggan], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Pelanggan $pelanggan)
    {
        return response()->json(['data' => $pelanggan], 200);
    }

    public function update(PelangganRequest $request, Pelanggan $pelanggan)
    {
        try {
            DB::beginTransaction();
            $pelanggan->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $pelanggan], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Pelanggan $pelanggan)
    {
        try {
            DB::beginTransaction();
            $pelanggan->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
