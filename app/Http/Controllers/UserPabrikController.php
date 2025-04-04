<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Supir;
use App\Models\Operator;
use App\Models\Pelanggan;
use App\Models\UserPabrik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserPabrikRequest;
use App\Http\Resources\UserPabrikResource;

class UserPabrikController extends Controller
{
    public function index(Request $request)
    {
        $dataQuery = User::with(['pelanggan', 'operator', 'supir', 'admin', 'userPabrik'])->orderBy('name', 'ASC');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $dataQuery->where('name', 'LIKE', '%' . $search . '%');
        }

        $limit = $request->filled('limit') ? $request->limit : 0;
        if ($limit) {
            $data = $dataQuery->paginate($limit);
            $resourceCollection = $data->map(function ($item) {
                return new UserPabrikResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => UserPabrikResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(UserPabrikRequest $request)
    {
        try {
            DB::beginTransaction();
            $UserPabrik = UserPabrik::create($request->all());
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $UserPabrik], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = UserPabrik::with(['pelanggan', 'operator', 'supir', 'admin'])->findOrFail($id);
        return response()->json(['data' => new UserPabrikResource($dataQuery)], 200);
    }

    public function update(UserPabrikRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $UserPabrik = UserPabrik::findOrFail($id);
            $UserPabrik->update($request->all());
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $UserPabrik], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $UserPabrik = UserPabrik::findOrFail($id);
            $UserPabrik->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat menghapus: ' . $e->getMessage()], 500);
        }
    }
}
