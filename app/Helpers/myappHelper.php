<?php

use App\Models\BerangkatTimbang;
use Carbon\Carbon;
use App\Models\User;
use App\Models\TimbangNota;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\File as FileFacade;

if (!function_exists('generateNomorNota')) {
    function generateNomorNota($waktu, $jenis = 'timbang')
    {
        $tahun = Carbon::parse($waktu)->format('Y');
        if ($jenis == 'timbang') {
            $format = "$tahun-01-";
            $lastNota = TimbangNota::where('nomor_nota', 'like', "$format%")->orderBy('nomor_nota', 'desc')->first();
        } elseif ($jenis == 'berangkat') {
            $format = "$tahun-02-";
            $lastNota = BerangkatTimbang::where('nomor_nota', 'like', "$format%")->orderBy('nomor_nota', 'desc')->first();
        }

        if ($lastNota) {
            $lastNumber = (int) substr($lastNota->nomor_nota, 8);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $formattedNumber = str_pad($newNumber, 6, '0', STR_PAD_LEFT);
        $nomorNota = $format . $formattedNumber;
        return $nomorNota;
    }
}

//sesuaikan rancangan database
if (!function_exists('daftarAkses')) {
    function daftarAkses($user_id = null, $getUser = null)
    {
        $listAkses = [];
        $akses_id = [
            'admin' => '',
            'operator' => '',
            'pelanggan' => '',
            'supir' => '',
            'user_pabrik' => '',
        ];

        if ($user_id) {
            $getUser = User::where('id', $user_id)
                ->with(['admin.grup', 'operator.grup', 'pelanggan.grup', 'supir.grup', 'userPabrik.pabrik', 'userPabrik.grup'])
                ->first();

            if (is_null($getUser)) {
                return ['listAkses' => [], 'akses_id' => $akses_id];
            }
        }
        if ($getUser) {
            // dd($getUser);
            // echo count($getUser->admin);
            if (count($getUser->admin) > 0) {
                $akses = $getUser->admin[0];
                $listAkses[] = ['grup_id' => $akses->grup->id, 'nama' => $akses->grup->nama, 'id' => $akses->id];
                $akses_id['admin'] = $akses->id;
            }
            if (count($getUser->operator) > 0) {
                $akses = $getUser->operator[0];
                $listAkses[] = ['grup_id' => $akses->grup->id, 'nama' => $akses->grup->nama, 'id' => $akses->id];
                $akses_id['operator'] = $akses->id;
            }
            if (count($getUser->pelanggan) > 0) {
                $akses = $getUser->pelanggan[0];
                $listAkses[] = ['grup_id' => $akses->grup->id, 'nama' => $akses->grup->nama, 'id' => $akses->id];
                $akses_id['pelanggan'] = $akses->id;
            }
            if (count($getUser->supir) > 0) {
                $akses = $getUser->supir[0];
                $listAkses[] = ['grup_id' => $akses->grup->id, 'nama' => $akses->grup->nama, 'id' => $akses->id];
                $akses_id['supir'] = $akses->id;
            }
            if (count($getUser->userPabrik) > 0) {
                $akses = $getUser->userPabrik[0];
                $listAkses[] = ['grup_id' => $akses->grup->id, 'nama' => $akses->grup->nama, 'id' => $akses->id];
                $akses_id['user_pabrik'] = $akses->id;
            }
        }
        $ret = ['daftar' => $listAkses, 'akses_id' => $akses_id];
        // dd($ret);
        return json_decode(json_encode($ret));
    }
}

if (!function_exists('dataPeserta')) {
    function dataPeserta($user_id)
    {
        $dt = Peserta::with(['user'])->where('user_id', $user_id)->get();
        if ($dt->isEmpty()) {
            return [];
        }
        return json_decode(json_encode($dt));
    }
}

if (!function_exists('anchor')) {
    function anchor($url, $text)
    {
        return '<a href="' . $url . '">' . $text . '</a>';
    }
}

if (!function_exists('dbDateTimeFormat')) {
    function dbDateTimeFormat($waktuDb, $format = 'Y-m-d H:i:s')
    {
        return Carbon::parse($waktuDb)->timezone('Asia/Makassar')->format($format);
    }
}

if (!function_exists('generateSlug')) {
    function generateSlug($judul, $waktu)
    {
        $disallowed_chars = array(
            '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '{', '}', '[', ']', '|', '\\', ';', ':', '"', '<', '>', ',', '.', '/', '?',
            ' ', "'", ' '
        );
        $judul = str_replace(' ', '-', $judul);
        $judul = str_replace($disallowed_chars, ' ', $judul);
        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $judul));

        $timestamp = strtotime($waktu);

        $tgl = date('y', $timestamp) + date('j', $timestamp) + date('n', $timestamp) + date('w', $timestamp);
        $waktu = date('H', $timestamp) + date('i', $timestamp);

        $generateWaktu = ($tgl + $waktu + rand(1, 999)) . '-' . date('s', $timestamp);
        $finalSlug = $slug . '-' . $generateWaktu;
        return $finalSlug;
    }
}


if (!function_exists('ukuranFile')) {
    function ukuranFile($size)
    {
        $satuan = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $satuan[$i];
    }
}

if (!function_exists('generateUniqueFileName')) {
    function generateUniqueFileName()
    {
        return $randomString = time() . Str::random(22);
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($request, $reqFileName = 'file', $storagePath = null, $fileName = null)
    {
        try {
            $uploadedFile = $request->file($reqFileName);
            if (!$uploadedFile->isValid()) {
                return false;
            }

            $originalFileName = $uploadedFile->getClientOriginalName();
            $ukuranFile = $uploadedFile->getSize();
            $tipeFile = $uploadedFile->getMimeType();
            $ext = $uploadedFile->getClientOriginalExtension();
            if (!$storagePath)
                $storagePath = 'uploads/' . date('Y') . '/' . date('m');

            if (!File::isDirectory(public_path($storagePath))) {
                File::makeDirectory(public_path($storagePath), 0755, true);
            }

            if (!$fileName)
                $fileName = generateUniqueFileName();
            $fileName .= '.' . $ext;

            $uploadedFile->move(public_path($storagePath), $fileName);
            $fileFullPath = public_path($storagePath . '/' . $fileName);
            chmod($fileFullPath, 0755);
            $path = $storagePath . '/' . $fileName;
            return [
                'path' => $path,
                'jenis' => $tipeFile,
                'ukuran' => ($ukuranFile / 1024),
            ];
        } catch (\Exception $e) {
            return 'Gagal mengunggah file. ' . $e->getMessage();
        }
    }
}

if (!function_exists('updateTokenUsed')) {
    function updateTokenUsed()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $token = $user->tokens->last();
            if ($token) {
                $token->forceFill([
                    'created_at' => now(),
                    'last_used_at' => now(),
                ])->save();
            }
        }
    }
}

if (!function_exists('ambilKata')) {
    function ambilKata($text, $limit = 25)
    {
        $text = strip_tags($text);
        $words = preg_split("/[\s,]+/", $text);
        $shortenedText = implode(' ', array_slice($words, 0, $limit));
        if (str_word_count($text) > $limit) {
            $shortenedText .= '...';
        }
        return $shortenedText;
    }
}

if (!function_exists('hapusFile')) {
    function hapusFile($data, $field)
    {
        if (!empty($data->$field) && File::exists($data->$field)) {
            File::delete($data->$field);
        }
    }
}

if (!function_exists('jenisFile')) {
    function jenisFile($path = null)
    {
        $retval = '';
        if ($path) {
            $parts = explode('.', $path);
            $ext = end($parts);
            $ext = strtolower($ext);
            if ($ext == 'pdf') {
                $retval = 'pdf';
            } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $retval = 'img';
            } elseif (in_array($ext, ['doc', 'docx'])) {
                $retval = 'doc';
            } else {
                $retval = 'etc';
            }
        }
        return $retval;
    }
}
