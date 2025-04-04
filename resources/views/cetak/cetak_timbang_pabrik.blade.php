<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak</title>
    <link rel="stylesheet" href="styles.css"> <!-- Opsional: Tambahkan file CSS eksternal -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            vertical-align: top; /* Align text ke atas */
        }

        td ul {
            margin: 0; /* Menghapus margin bawaan */
            padding: 0; /* Menghapus padding bawaan */
            list-style-position: inside; /* (Opsional) Agar titik li sejajar dengan teks */
        }

        td li {
            margin: 0;
            padding: 0;
        }          
    </style>
</head>
<body>

    <header>
        <h1>Cetak Timbang Pabrik</h1>
    </header>


    <main>
        {{-- <div style="overflow-x:auto; width:100%"> --}}
            <table id="tabel-cetak" >
                <thead>
                    <tr >
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">Mobil/ Supir</th>
                        <th rowspan="2" style="width:200px">RAM</th>
                        <th colspan="2">Ram Timbang (Kg)</th>
                        <th rowspan="2" style="width:200px">Pabrik Tujuan</th>
                        <th rowspan="2" >Berat Timbang Pabrik</th>
                        <th rowspan="2" style="width:100px">TP</th>
                        <th rowspan="2">Pabrik Timbang (Kg)</th>
                        <th rowspan="2" style="width:100px">Penyusutan/ Kelebihan</th>
                        <th rowspan="2" style="width:130px">Harga</th>
                        <th rowspan="2" style="width:130px">Harga x berat pabrik</th>
                        <th rowspan="2" style="width:150px">Jumlah</th>
                        <th colspan="3">Biaya</th>
                        <th rowspan="2" style="width:150px">Bersih</th>
                    </tr>
                    <tr >
                        <th >Timbang Kotor (Kg)</th>
                        <th >Timbang Bersih (Kg)</th>
                        <th style="width:150px">Loading</th>
                        <th style="width:150px">Bongkar</th>
                        <th style="width:150px">Sewa Mobil</th>
                    </tr>
                </thead>
                <tbody id="data-list">
                    <!-- Data akan dimuat di sini -->
                </tbody>                
            </table>        
            <a href="javascript:;" id="selanjutnya" style="display:none;">Selanjutnya</a>

        {{-- </div> --}}
    </main>

    <footer>
        <p>&copy; 2025 Semua Hak Dilindungi.</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>    
    <script src="{{ url('js/token.js') }}"></script>
    <script src="{{ url('js/general.js') }}"></script>
    <script>
        const vUrl="{{ url('/') }}";
        const vLimit=100;
        var vPage=1;
        $(document).ready(function() {
            vPage=1;
            $('#selanjutya').click(function() {
                offset += vLimit;
                load();
            })
            $('#selanjutnya').click(function(){
                vPage++;
                load();
            });

            load();
            function load() {
                $.ajax({
                    url: vUrl+'/api/timbang-berangkat',
                    type: 'GET',
                    data: {
                        limit: vLimit,
                        page: vPage,
                    },
                    success: function(response) {
                        if(response.current_page<response.last_page){
                            $('#selanjutnya').show();
                        }else{
                            $('#selanjutnya').hide();
                        }
                        renderData(response.data);
                    },
                    error: function(error) {
                        alert('data tidak ditemukan');
                        $('#selanjutya').hide();
                    }
                });
            }

            //untuk render dari database
            function renderData(data) {
                const dataList = $('#data-list');
                let no = dataList.children('tr').length + 1; // Nomor urut yang terus bertambah
                if (data.length > 0) {
                    $.each(data, function(index, dt) {
                        let ram_asal;
                        let ram_berat_bersih;
                        let ram_berat_kotor;
                        let total_ram_berat_bersih=0;
                        let total_ram_berat_kotor=0;

                        let pabrik_berat_bersih;
                        let tp;
                        let harga_kotor;
                        let pabrik_berat_kotor;
                        let harga;
                        if(dt.berangkat_timbang.length>0){
                            ram_asal=`<ul>`;
                            ram_berat_bersih=`<ul>`;
                            ram_berat_kotor=`<ul>`;
                            pabrik_berat_bersih=`<ul>`;
                            pabrik_berat_kotor=`<ul>`;
                            harga_kotor=`<ul>`;
                            harga=`<ul>`;
                            tp=`<ul>`;
                            $.each(dt.berangkat_timbang, function(index, brngkt_tmbng) {
                                // console.log(brngkt_tmbng);
                                total_ram_berat_bersih+=brngkt_tmbng.ram_timbang_bersih;
                                total_ram_berat_kotor+=brngkt_tmbng.ram_timbang_kotor;
                                ram_asal+=`<li>${brngkt_tmbng.ram.nama}</li>`;
                                ram_berat_bersih+=`<li>${brngkt_tmbng.ram_timbang_bersih}</li>`;
                                ram_berat_kotor+=`<li>${brngkt_tmbng.ram_timbang_kotor}</li>`;
                                pabrik_berat_bersih+=`<li>${brngkt_tmbng.pabrik_timbang_bersih}</li>`;
                                pabrik_berat_kotor+=`<li>${brngkt_tmbng.pabrik_timbang_kotor}</li>`;
                                harga+=`<li>Rp. ${ribuanId(brngkt_tmbng.harga)}</li>`;
                                harga_kotor+=`<li>Rp. ${ribuanId(brngkt_tmbng.harga*brngkt_tmbng.pabrik_timbang_kotor)}</li>`;
                                tp+=`<li>${dt.berangkat_pabrik.tp}</li>`;
                            });
                            ram_asal+=`</ul>`;
                            ram_berat_bersih+=`</li>`;
                            ram_berat_kotor+=`</li>`;
                            ram_berat_bersih+=`<hr>${total_ram_berat_bersih}`;
                            ram_berat_kotor+=`<hr>${total_ram_berat_kotor}`;
                            pabrik_berat_bersih+=`</li>`;
                            pabrik_berat_kotor+=`</li>`;
                            harga+=`</li>`;
                            harga_kotor+=`</li>`;
                            tp+=`</li>`;
                        }

                        row = `<tr>
                                    <td>${no++}</td>
                                    <td>${dt.tanggal}</td>
                                    <td>${dt.berangkat_supir.user.name}/${dt.mobil_nama}</td>
                                    <td>${ram_asal}</td>
                                    <td>${ram_berat_kotor}</td>
                                    <td>${ram_berat_bersih}</td>
                                    <td>${dt.pabrik_nama}</td>
                                    <td>${dt.berangkat_pabrik.timbang_kotor}</td>
                                    <td>${tp}</td>
                                    <td>${pabrik_berat_kotor}</td>
                                    <td>${dt.berangkat_pabrik.nilai_susut} Kg</td>
                                    <td>${harga}</td>
                                    <td>${harga_kotor}</td>
                                    <td>Rp. ${ribuanId(dt.berangkat_pabrik.harga_sawit)}</td>
                                    <td>Rp. ${ribuanId(dt.berangkat_pabrik.biaya_loading)}</td>
                                    <td>Rp. ${ribuanId(dt.berangkat_pabrik.biaya_bongkar)}</td>
                                    <td>Rp. ${ribuanId(dt.berangkat_pabrik.sewa_mobil)}</td>
                                    <td>Rp. ${ribuanId(dt.berangkat_pabrik.bersih)}</td>
                                </tr>`;
                        dataList.append(row);
                    });
                }
            }

        });
    </script>
    
</body>
</html>


{{-- script costum --}}
