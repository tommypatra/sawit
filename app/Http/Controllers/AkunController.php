<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Supir;
use App\Models\Operator;
use App\Models\Pelanggan;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AkunRequest;
use App\Http\Resources\AkunResource;
use Illuminate\Support\Facades\Hash;
use App\Models\UserPabrik;

class AkunController extends Controller
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
                return new AkunResource($item);
            });
            $data->setCollection($resourceCollection);
        } else {
            $data = ['data' => AkunResource::collection($dataQuery->get())];
        }
        return response()->json($data);
    }

    public function store(AkunRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['password'] = Hash::make($request->password);
            $akun = User::create($data);
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $akun], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $dataQuery = User::with(['pelanggan', 'operator', 'supir', 'admin', 'userPabrik'])->findOrFail($id);
        return response()->json(['data' => new AkunResource($dataQuery)], 200);
    }

    public function update(AkunRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $Akun = User::findOrFail($id);

            $data = $request->validated();
            // dd($request->password);
            if (!empty($request->password))
                $data['password'] = Hash::make($request->password);
            else
                unset($data['password']);
            $Akun->update($data);
            DB::commit();
            return response()->json(['message' => 'berhasil diperbarui', 'data' => $Akun], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $Akun = User::findOrFail($id);
            $Akun->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat menghapus: ' . $e->getMessage()], 500);
        }
    }

    public function daftarAkses(Request $request, $id)
    {
        $daftarAkses = daftarAkses($id);
        return response()->json($daftarAkses, 200);
    }

    public function simpanAksesUser(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request);
            $akses = $request->input('akses');
            $user_id = $request->input('user_id');
            $save_akses = [];
            foreach ($akses as $i => $dp) {
                $data_save = [
                    'user_id' => $user_id,
                    'grup_id' => $dp['grup_id'],
                ];
                if ($dp['grup_id'] == 1)
                    $save_akses[] = Admin::create($data_save);
                elseif ($dp['grup_id'] == 2)
                    $save_akses[] = Operator::create($data_save);
                elseif ($dp['grup_id'] == 3)
                    $save_akses[] = Pelanggan::create($data_save);
                elseif ($dp['grup_id'] == 4)
                    $save_akses[] = Supir::create($data_save);
                elseif ($dp['grup_id'] == 5)
                    $save_akses[] = Pabrik::create($data_save);
            }
            DB::commit();
            return response()->json(['message' => 'data baru berhasil dibuat', 'data' => $save_akses], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'terjadi kesalahan saat membuat data baru: ' . $e->getMessage()], 500);
        }
    }
    public function hapusAksesUser(Request $request)
    {
        try {
            $id = $request->input('id');
            $akses = $request->input('akses');
            $grup_id = $request->input('grup_id');

            // Mapping akses ke model yang sesuai
            $models = [
                '1' => Admin::class,
                '2' => Operator::class,
                '3' => Pelanggan::class,
                '4' => Supir::class,
                '5' => UserPabrik::class,
            ];
            if (!isset($models[$grup_id])) {
                return response()->json(['message' => 'Jenis akses tidak valid!'], 400);
            }
            DB::beginTransaction();
            $model = $models[$grup_id]::findOrFail($id);
            $model->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage()], 500);
        }
    }
}
