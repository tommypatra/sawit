<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(AuthRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)
                ->with(['admin.grup', 'operator.grup', 'pelanggan.grup', 'supir.grup', 'userPabrik.pabrik', 'userPabrik.grup'])
                ->first();

            $daftarAkses = daftarAkses(null, $user);
            // die;
            $token = $user->createToken('api_token')->plainTextToken;
            // $token = $user->createToken('api-token', ['user_id' => $user->id, 'user_group' => $daftarAkses])->plainTextToken;


            $respon_data = [
                'message' => 'Login successful',
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'access_token' => $token,
                'akses_grup_id' => $daftarAkses->daftar[0]->grup_id,
                'daftar_akses' => $daftarAkses->daftar,
                'akses_id' => $daftarAkses->akses_id,
                'token_type' => 'Bearer',
            ];
            // dd($respon_data);
            return response()->json($respon_data, 200);
        }
        return response()->json(['message' => 'user not found'], 404);
    }

    function tokenGrupCek($grup_nama = '')
    {
        $user_id = auth()->check() ? auth()->user()->id : null;
        if ($user_id) {
            if ($grup_nama === '') {
                //mendapatkan user_group dari query 4 tabel kembali
                $daftar_akses = daftarAkses($user_id);
                $daftar_akses_array = json_decode(json_encode($daftar_akses), true);
                $index = array_search($grup_nama, array_column($daftar_akses_array['daftar'], 'nama'));
            } else {
                $index = true;
            }

            if ($index !== false) {
                return response()->json(['message' => 'token valid'], 200);
            }
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $user_id = $request->user() ? $request->user()->id : null;
        if ($user_id) {
            if ($request->user()->tokens()->count() > 0) {
                $request->user()->tokens()->delete();
            }
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
