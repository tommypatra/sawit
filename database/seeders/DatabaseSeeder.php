<?php

namespace Database\Seeders;

use App\Models\Ram;
use App\Models\Grup;
use App\Models\User;
use App\Models\Admin;
use App\Models\Mobil;
use App\Models\Supir;
use App\Models\Pabrik;
use App\Models\Operator;
use App\Models\Pelanggan;
use App\Models\UserPabrik;
use App\Models\SumberBayar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //definisiakn grup
        $dtdef = [
            ['nama' => 'Admin'],
            ['nama' => 'Operator'],
            ['nama' => 'Pelanggan'],
            ['nama' => 'Supir'],
            ['nama' => 'Pabrik'],
        ];
        //input grp
        foreach ($dtdef as $dt) {
            Grup::create([
                'nama' => $dt['nama'],
            ]);
        }

        $dtdef = [
            //admin 1
            ['name' => 'Admin', 'email' => 'admin@app.com', 'alamat' => 'Kendari', 'hp' => '08520000001'],
            //operator 2
            ['name' => 'Operator', 'email' => 'op@app.com', 'alamat' => 'Kendari', 'hp' => '08520000002'],
            //pelanggan 3-6
            ['name' => 'H. Aco', 'email' => 'aco@app.com', 'alamat' => 'Kendari', 'hp' => '08520000003'],
            ['name' => 'Andri', 'email' => 'andri@app.com', 'alamat' => 'Kendari', 'hp' => '08520000004'],
            ['name' => 'Hj. Marni', 'email' => 'marni@app.com', 'alamat' => 'Kendari', 'hp' => '08520000005'],
            ['name' => 'Hj. Besse', 'email' => 'besse@app.com', 'alamat' => 'Kendari', 'hp' => '08520000006'],
            //sopir 7-10
            ['name' => 'Supriadin', 'email' => 'supriadin@app.com', 'alamat' => 'Kendari', 'hp' => '08520000007'],
            ['name' => 'Mujib', 'email' => 'mujib@app.com', 'alamat' => 'Kendari', 'hp' => '08520000008'],
            ['name' => 'Marlin', 'email' => 'marlin@app.com', 'alamat' => 'Kendari', 'hp' => '08520000009'],
            ['name' => 'Hardi', 'email' => 'hardi@app.com', 'alamat' => 'Kendari', 'hp' => '08520000010'],
            //pabrik 11 - 13
            ['name' => 'Martono', 'email' => 'martono@app.com', 'alamat' => 'Kendari', 'hp' => '08520000011'],
            ['name' => 'Sir. Mandar', 'email' => 'mandar@app.com', 'alamat' => 'Kendari', 'hp' => '08520000012'],
            ['name' => 'Weng Seng', 'email' => 'weng@app.com', 'alamat' => 'Kendari', 'hp' => '08520000013'],
        ];

        //input user
        foreach ($dtdef as $dt) {
            User::create([
                'name' => $dt['name'],
                'email' => $dt['email'],
                'alamat' => $dt['alamat'],
                'hp' => $dt['alamat'],
                'password' => Hash::make('00000000'),
            ]);
        }


        $dtdef = [
            ['nama' => 'PT. Sawit Maju',  'alamat' => 'Konsel', 'hp' => '08520000033', 'biaya_bongkar' => 20, 'biaya_supir' => 200000, 'biaya_mobil' => 150000],
            ['nama' => 'PT. Murni Sawit',  'alamat' => 'Bandara', 'hp' => '08520000034', 'biaya_bongkar' => 15, 'biaya_supir' => 175000, 'biaya_mobil' => 125000],
            ['nama' => 'PT. Sawit Sejahtera',  'alamat' => 'Bandara', 'hp' => '08520000035', 'biaya_bongkar' => 10, 'biaya_supir' => 150000, 'biaya_mobil' => 100000],
        ];

        //input pabrik
        foreach ($dtdef as $dt) {
            Pabrik::create([
                'nama' => $dt['nama'],
                'alamat' => $dt['alamat'],
                'hp' => $dt['alamat'],
                'biaya_bongkar' => $dt['biaya_bongkar'],
                'biaya_supir' => $dt['biaya_supir'],
                'biaya_mobil' => $dt['biaya_mobil'],
            ]);
        }


        $dtdef = [
            ['nama' => 'Kijang Beramal',  'warna' => 'hitam', 'merk' => 'Toyota', 'no_polisi' => 'DT 1234 CH'],
            ['nama' => 'Konsel Jaya',  'warna' => 'hitam', 'merk' => 'Suzuki', 'no_polisi' => 'DT 9734 AH'],
            ['nama' => 'Kijang 1',  'warna' => 'hijau', 'merk' => 'Wuling', 'no_polisi' => 'DT 1144 CH'],
            ['nama' => 'Kijang 2',  'warna' => 'biru', 'merk' => 'Mitsubisi', 'no_polisi' => 'DT 6542 DH'],
        ];

        //input mobil
        foreach ($dtdef as $dt) {
            Mobil::create([
                'nama' => $dt['nama'],
                'warna' => $dt['warna'],
                'merk' => $dt['merk'],
                'no_polisi' => $dt['no_polisi'],
            ]);
        }

        $dtdef = [
            ['nama' => 'RAM MOWILA', 'alamat' => 'Mowila'],
            ['nama' => 'RAM RANOMEETO', 'alamat' => 'Ranomeeto'],
            ['nama' => 'RAM KONDA', 'alamat' => 'Konda'],
        ];

        //input ram
        foreach ($dtdef as $dt) {
            Ram::create([
                'nama' => $dt['nama'],
                'alamat' => $dt['alamat'],
                'is_aktif' => 1,
            ]);
        }


        $dtdef = [
            ['nama' => 'RAM'],
            ['nama' => 'BANK BRI'],
            ['nama' => 'RUMAH'],
        ];

        //input sumber bayar
        foreach ($dtdef as $dt) {
            SumberBayar::create([
                'nama' => $dt['nama'],
            ]);
        }

        //akun admin
        Admin::create([
            'user_id' => 1,
            'grup_id' => 1,
            'is_aktif' => 1,
        ]);

        //akun operator
        Operator::create([
            'user_id' => 2,
            'grup_id' => 2,
            'is_aktif' => 1,
        ]);

        //akun operator
        Operator::create([
            'user_id' => 1,
            'grup_id' => 2,
            'is_aktif' => 1,
        ]);

        //akun pelanggan
        for ($i = 3; $i <= 6; $i++)
            Pelanggan::create([
                'user_id' => $i,
                'grup_id' => 3,
                'is_aktif' => 1,
            ]);

        //akun pabrik
        UserPabrik::create(['user_id' => 1, 'grup_id' => 5, 'is_aktif' => 1]);
        UserPabrik::create(['user_id' => 11, 'grup_id' => 5, 'is_aktif' => 1]);
        UserPabrik::create(['user_id' => 12, 'grup_id' => 5, 'is_aktif' => 1]);
        UserPabrik::create(['user_id' => 13, 'grup_id' => 5, 'is_aktif' => 1]);

        //akun sopir
        for ($i = 7; $i <= 10; $i++)
            Supir::create([
                'user_id' => $i,
                'grup_id' => 4,
                'is_aktif' => 1,
            ]);
    }
}
