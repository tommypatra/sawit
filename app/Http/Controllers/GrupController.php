<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupRequest;
use App\Http\Resources\GrupResource;
use App\Models\Grup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrupController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Grup::orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where('nama', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new GrupResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => GrupResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(GrupRequest $request)
    {
        try {
            DB::beginTransaction();
            $Grup = Grup::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $Grup], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Grup $Grup)
    {
        return response()->json(['data' => $Grup], 200);
    }

    public function update(GrupRequest $request, Grup $Grup)
    {
        try {
            DB::beginTransaction();
            $Grup->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $Grup], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Grup $Grup)
    {
        try {
            DB::beginTransaction();
            $Grup->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
