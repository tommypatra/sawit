<?php

namespace App\Http\Controllers;

use App\Http\Requests\PabrikRequest;
use App\Http\Resources\PabrikResource;
use App\Models\Pabrik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PabrikController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = Pabrik::orderBy('nama', 'asc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'LIKE', '%' . $search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $search . '%')
                ->orWhere('hp', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new PabrikResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => PabrikResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(PabrikRequest $request)
    {
        try {
            DB::beginTransaction();
            $pabrik = Pabrik::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $pabrik], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show(Pabrik $pabrik)
    {
        return response()->json(['data' => $pabrik], 200);
    }

    public function update(PabrikRequest $request, Pabrik $pabrik)
    {
        try {
            DB::beginTransaction();
            $pabrik->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $pabrik], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Pabrik $pabrik)
    {
        try {
            DB::beginTransaction();
            $pabrik->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui : ' . $e->getMessage()], 500);
        }
    }
}
