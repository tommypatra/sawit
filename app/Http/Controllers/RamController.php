<?php

namespace App\Http\Controllers;

use App\Http\Requests\RamRequest;
use App\Http\Resources\RamResource;
use App\Models\Ram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RamController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Ram::orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where('nama', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new RamResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => RamResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(RamRequest $request)
    {
        try {
            DB::beginTransaction();
            $Ram = Ram::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $Ram], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Ram $Ram)
    {
        return response()->json(['data' => $Ram], 200);
    }

    public function update(RamRequest $request, Ram $Ram)
    {
        try {
            DB::beginTransaction();
            $Ram->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $Ram], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Ram $Ram)
    {
        try {
            DB::beginTransaction();
            $Ram->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
