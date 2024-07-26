<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerangkatMobil;
use App\Models\BerangkatSupir;
use App\Models\BerangkatPabrik;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\BerangkatPabrikRequest;
use App\Http\Resources\BerangkatPabrikResource;

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
            $berangkatPabrik = BerangkatPabrik::create($request->all());
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
            $berangkatPabrik->update($request->all());
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
}
