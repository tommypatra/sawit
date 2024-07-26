<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupirRequest;
use App\Http\Resources\SupirResource;
use App\Models\Supir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupirController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Supir::with(['user'])->orderBy('id', 'DESC');

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
                return new SupirResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => SupirResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(SupirRequest $request)
    {
        try {
            DB::beginTransaction();
            $supir = Supir::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $supir], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Supir $supir)
    {
        return response()->json(['data' => $supir], 200);
    }

    public function update(SupirRequest $request, Supir $supir)
    {
        try {
            DB::beginTransaction();
            $supir->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $supir], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Supir $supir)
    {
        try {
            DB::beginTransaction();
            $supir->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
