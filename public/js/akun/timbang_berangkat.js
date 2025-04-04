var user_grup_akses = 'operator';
var page;
tokenCek(user_grup_akses);
var auth_grup_akses=cariGrupAkses(user_grup_akses);
var endpoint=base_url+'/api/timbang-berangkat';
var vJumlah_muatan;
var vTotal_berat;
var pabrik_biaya_bongkar;
var pabrik_biaya_supir;
var pabrik_biaya_mobil;


function hitungTp(){
    let pabrik_timbang_kotor=$('#pabrik_timbang_kotor').val();
    if(pabrik_timbang_kotor>0){
        let persenSelisih = ((pabrik_timbang_kotor - vTotal_berat) / vTotal_berat) * 100;            
        $('#tp').val(persenSelisih.toFixed(2));
        console.log(50*pabrik_timbang_kotor,pabrik_biaya_bongkar*pabrik_timbang_kotor,pabrik_biaya_mobil);
        $('#biaya_loading').val(50*pabrik_timbang_kotor);
        $('#biaya_bongkar').val(pabrik_biaya_bongkar*pabrik_timbang_kotor);
        $('#sewa_mobil').val(pabrik_biaya_mobil);

        $('#pabrik_timbang_bersih').val($('#pabrik_timbang_kotor').val());
        hitungSetelahTimbangPabrik();
    }
}

function hitungSetelahTimbangPabrik(){
    let tp=$('#tp').val();
    let pabrik_timbang_kotor=$('#pabrik_timbang_kotor').val();
    let total_pabrik=0;
    let rasio = pabrik_timbang_kotor / vTotal_berat;

    $('.body_muatan2 tr.data').each(function() {
        let row = $(this);
        let ram_awal = row.find('.ram_timbang_kotor').text();        
        let ram_baru = Math.round(ram_awal * rasio);

        total_pabrik+=ram_baru;
        row.find('.persen').text(`${tp}`);
        row.find('.harga').html(`<input type="number" class="form-control number_harga" value="0" oninput="hitungHarga()">`);
        row.find('.pabrik_timbang_kotor').html(`<input type="number" class="form-control number_pabrik_timbang_kotor" value="${ram_baru}" oninput="hitungTotalAkhir()">`);
    });
    hitungTotalAkhir();
}

function hitungTotalAkhir(){
    var ram_total = 0;
    var pabrik_total=0;

    $('.body_muatan2 tr.data').each(function() {
        let row=$(this);
        ram_total+=parseInt(row.find('.ram_timbang_kotor').text());
        pabrik_total+=parseInt(row.find('.number_pabrik_timbang_kotor').val());
    });
    console.log(ram_total,pabrik_total);
    $('.body_muatan2 tr#total_timbang').find('.ram_timbang_kotor').text(ram_total);    
    $('.body_muatan2 tr#total_timbang').find('.pabrik_timbang_kotor').text(pabrik_total);    
}

function hitungHarga(){
    var total = 0;
    var biaya_bongkar=parseInt($('#biaya_bongkar').val());
    var biaya_loading=parseInt($('#biaya_loading').val());
    var sewa_mobil=parseInt($('#sewa_mobil').val());

    $('.body_muatan2 tr.data').each(function() {
        const row=$(this);
        const berat=parseInt(row.find('.number_pabrik_timbang_kotor').val())
        const harga=parseInt(row.find('.number_harga').val())
        total+=(berat*harga)
    });
    bersih =total-(biaya_bongkar+biaya_loading+sewa_mobil);
    $('#harga_sawit').val(total);   
    $('#bersih').val(bersih);   
}

$(document).ready(function() {


    // console.log(akses_id.operator);

    dataLoad();
    dataPabrik();
    dataMobil();
    dataSupir();
    dataRam();

    function refreshData(){
        dataLoad();    
    }

    $('#berangkat-tab').click(function(){
        dataLoad(1);
    });

    $('#pabrik-tab').click(function(){
        dataLoad(1);
    });

    $('#search-input').on('keypress', function(event) {
        if (event.which === 13) {
            dataLoad(1);            
        }
    });    

    $('.refresh-data').click(function(){
        refreshData();
    });

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });
    
    $('.datepickertime').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD HH:mm:ss',
        time: true,
    });

    function dataPabrik() {
        var url = base_url + '/api/pabrik';
        var idselect='#pabrik_id';

        $(idselect).empty();
        fetchData(url, function(response) {
            $.each(response.data, function(key, value) {
                $(idselect).append($('<option>', {
                    value: value.id,
                    text: value.nama,
                }));
            });
        });
        // $(idselect).trigger('change');
        $(idselect).select2({
            // dropdownParent: $('#modal-form'),
        });    
    }
    
    function dataRam() {
        var url = base_url + '/api/ram';
        var selectElement='#ram_id';

        $(selectElement).empty();
        const option_def = `<option value="">- pilih -</option>`;
        $(selectElement).append(option_def);    

        fetchData(url, function(response) {
            $.each(response.data, function(key, value) {
                $(selectElement).append($('<option>', {
                    value: value.id,
                    text: value.nama,
                }));
            });
            $(selectElement).trigger('change');
            $(selectElement).select2({
                dropdownParent: $('#modal-form'),
            });    
        });
    }


    function dataSupir() {
        var url = base_url + '/api/supir';

        var selectElement='#supir_id';

        $(selectElement).empty();
        const option_def = `<option value="">- pilih -</option>`;
        $(selectElement).append(option_def);    
        
        fetchData(url, function(response) {
            $.each(response.data, function(key, value) {
                $(selectElement).append($('<option>', {
                    value: value.id,
                    text: value.nama,
                }));
            });
            $(selectElement).trigger('change');
            $(selectElement).select2({
                dropdownParent: $('#modal-form'),
            });    
        });
    }    

    // var supirData=[];
    // function dataSupir() {
    //     var url = base_url + '/api/supir';
    //     fetchData(url, function(response) {
    //         supirData=response.data;
    //     });
    // }

    function dataMobil() {
        var url = base_url + '/api/mobil';
        var selectElement='#mobil_id';

        $(selectElement).empty();
        const option_def = `<option value="">- pilih -</option>`;
        $(selectElement).append(option_def);    

        fetchData(url, function(response) {
            $.each(response.data, function(key, value) {
                $(selectElement).append($('<option>', {
                    value: value.id,
                    text: value.nama+' ('+value.no_polisi+')',
                }));
            });
        });
        // $(selectElement).trigger('change');
        $(selectElement).select2({
            // dropdownParent: $('#modal-form'),
        });    

    }

    function dataLoad(vPage) {
        page = vPage;
        var search = $('#search-input').val();
        var grup = $('.nav-tabs .nav-link.active').attr('data-value');
        var url = endpoint + '?grup=' + grup + '&page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            // console.log(response);
            renderData(response);
        },true);
    }

    // Handle item-paging limit change
    // $('.item-paging').on('click', function() {
    //     vLimit = $(this).data('nilai');
    //     dataLoad();
    // })

    $(document).on('click', '.hapus-pabrik-berangkat', function() {
        var id = $(this).data('id');
        deleteData(base_url+'/api/timbang-pabrik-mobil', id, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            refreshData();
        });                        
    });

    // Handle page change
    $(document).on('click', '#pagination .page-link', function() {
        var page = $(this).data('page');
        dataLoad(page);
    });    
    
    //untuk render dari dataLoad
    function renderData(response) {
        const dataList = $('#data-list');
        const pagination = $('#pagination');
        let no = (response.current_page - 1) * response.per_page + 1;
        dataList.empty();
        if (response.data.length > 0) {
            $.each(response.data, function(index, dt) {

                let muatan="";
                let ram_timbang_kotor=0;
                let ram_timbang_bersih=0;
                if(dt.berangkat_timbang.length>0){
                    muatan="<ul>";
                    $.each(dt.berangkat_timbang, function(index_muatan, dt_muatan) {
                        ram_timbang_kotor+=dt_muatan.ram_timbang_kotor;
                        ram_timbang_bersih+=dt_muatan.ram_timbang_bersih;
                        muatan+=`<li>${dt_muatan.ram.nama} (${ribuanId(dt_muatan.ram_timbang_bersih)} Kg / ${ribuanId(dt_muatan.ram_timbang_kotor)} Kg) ${dt_muatan.operator.user.name}</li>`;        
                    });
                    muatan+="</ul><hr>";
                    muatan+=`Total Timbang Bersih/ Kotor : ${ribuanId(ram_timbang_bersih)} Kg / ${ribuanId(ram_timbang_kotor)} Kg`;
                }

                let supir=(dt.berangkat_supir.id)?dt.berangkat_supir.user.name:"";

                const row = `<tr>
                                <td>${no++}</td>
                                <td>
                                    <figure>
                                        <div class="fs-7">${dt.nomor_nota}</div>                                        
                                        <blockquote class="blockquote">
                                            <p>${dt.pabrik_nama}</p>
                                        </blockquote>
                                        <figcaption class="blockquote-footer">${dt.pabrik_alamat} (${dt.pabrik_hp})</figcaption>
                                    </figure>                            
                                </td>
                                <td>${muatan}</td>
                                <td>${dt.mobil_nama} (${dt.mobil_no_polisi}) ${supir}</td>
                                <td>${dt.operator_user_name} / ${dt.tanggal}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-timbang-pabrik" data-id="${dt.id}" data-total_berat="${ram_timbang_bersih}" data-jumlah_muatan="${dt.berangkat_timbang.length}" href="javascript:;"><i class="bi bi-wallet2"></i> Timbang Pabrik</a></li>
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-pencil-square"></i> Muatan</a></li>
                                            <li><a class="dropdown-item btn-hapus" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-trash"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="5">
                                    ${rincianPabik(dt)}
                                </td>
                                <td></td>
                            </tr>                            
                            `;
                dataList.append(row);
            });
            renderPagination(response, pagination);
        }else {
            const row = `<tr><td colspan="7">Tidak Ditemukan</td></tr>`;
            dataList.append(row);
        }
    }

    // untuk update nomor pada tabel daftar mobil
    function updateNomorUrut(elementId) {
        $(`${elementId} tr`).each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }


    $('#tambah_muatan').click(function(){
        var ram_id = $("#ram_id").val();
        var ram_nama = $("#ram_id").find('option:selected').text();
        var timbang_bersih = $("#timbang_bersih").val();    
        var timbang_kotor = $("#timbang_kotor").val();    
        if(ram_id && timbang_bersih && timbang_kotor){    
            tambahMuatan('.body_muatan1',ram_id,ram_nama,timbang_bersih,timbang_kotor,'',true);
        }
    });

    
    $(document).on('click', '.btn-timbang-pabrik', function() {
        showDataById(endpoint, $(this).data('id'), function(response) {    

            let tmp_total_berat=0;
            let tmp_total_pabrik=0;
            let elementId='.body_muatan2';
            let berangkat_pabrik_id=(response.data.berangkat_pabrik)?response.data.berangkat_pabrik.id:"";
            $(elementId).empty();
            tmp_jumlah=0;
            
            pabrik_biaya_bongkar=response.data.pabrik_biaya_bongkar;
            pabrik_biaya_supir=response.data.pabrik_biaya_supir;
            pabrik_biaya_mobil=response.data.pabrik_biaya_mobil;

            $('#form-timbang-pabrik #berangkat_pabrik_id').val(berangkat_pabrik_id);
            $('#form-timbang-pabrik #berangkat_mobil_id').val(response.data.id);

            if(response.data.berangkat_pabrik){
                $('#form-timbang-pabrik #pabrik_timbang_kotor').val(response.data.berangkat_pabrik.timbang_kotor);
                $('#form-timbang-pabrik #pabrik_timbang_bersih').val(response.data.berangkat_pabrik.timbang_bersih);

                $('#form-timbang-pabrik #biaya_loading').val(response.data.berangkat_pabrik.biaya_loading);
                $('#form-timbang-pabrik #biaya_bongkar').val(response.data.berangkat_pabrik.biaya_bongkar);
                $('#form-timbang-pabrik #sewa_mobil').val(response.data.berangkat_pabrik.sewa_mobil);

                $('#form-timbang-pabrik #tp').val(response.data.berangkat_pabrik.tp);
                $('#form-timbang-pabrik #harga_sawit').val(response.data.berangkat_pabrik.harga_sawit);
                $('#form-timbang-pabrik #bersih').val(response.data.berangkat_pabrik.bersih);
            }else{
                $('#form-timbang-pabrik #pabrik_timbang_kotor').val("");
                $('#form-timbang-pabrik #pabrik_timbang_bersih').val("");

                $('#form-timbang-pabrik #biaya_loading').val("");
                $('#form-timbang-pabrik #biaya_bongkar').val("");
                $('#form-timbang-pabrik #sewa_mobil').val("");

                $('#form-timbang-pabrik #tp').val("");
                $('#form-timbang-pabrik #harga_sawit').val("");
                $('#form-timbang-pabrik #bersih').val("");
            }
                        
            $.each(response.data.berangkat_timbang, function(index_muatan, dt_muatan) {
                tmp_jumlah++;
                tmp_total_berat+=dt_muatan.ram_timbang_kotor;
                tmp_total_pabrik+=dt_muatan.pabrik_timbang_kotor;
                const newRow = `<tr class="data" data-berangkat_timbang_id="${dt_muatan.id}" data-ram_id="${dt_muatan.ram_id}">
                        <td></td>
                        <td>${dt_muatan.ram.nama}</td>
                        <td class="ram_timbang_kotor">${dt_muatan.ram_timbang_kotor}</td>
                        <td class="persen">${dt_muatan.persen}</td>
                        <td class="pabrik_timbang_kotor"><input type="number" class="form-control number_pabrik_timbang_kotor valid" value="${dt_muatan.pabrik_timbang_kotor}" oninput="hitungTotalAkhir()" aria-invalid="false"></td>
                        <td class="harga"><input type="number" class="form-control number_harga valid" value="${dt_muatan.harga}" oninput="hitungHarga()" aria-invalid="false"></td>
                    </tr>`;
                $(elementId).append(newRow);
                updateNomorUrut(elementId);
            });

            $('#ram_berat_kotor_awal').val(tmp_total_berat);

            const newRow = `<tr id="total_timbang">
                                <td></td>
                                <td></td>
                                <td class="ram_timbang_kotor"></td>
                                <td class="persen"></td>
                                <td class="pabrik_timbang_kotor"></td>
                                <td class="harga"></td>
                            </tr>`;
            $(elementId).append(newRow);
            vJumlah_muatan=tmp_jumlah;
            vTotal_berat=tmp_total_berat;
            hitungTotalAkhir();
        });
        showModal('modal-timbang-pabrik');
    });

    $(document).on('click', '.btn-hapus-mobil', function() {
        var dt_muatan = $(this).closest('tr'); 
        var id = dt_muatan.data('berangkat_timbang_id');
        if(!id)
            dt_muatan.remove();
        else{
            deleteData(base_url+'/api/timbang-muatan', id, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                refreshData();
                dt_muatan.remove();
            });                        
        }
        updateNomorUrut();
    });   
    
    function timbangPabrik(jumlah_ram){
        alert(jumlah_ram);
    }

    function rincianPabik(dt) {
        let detail=dt.berangkat_pabrik;
        let bayarPabrik='';
        if(detail!==null){
            bayarPabrik = `
                <div class="accordion" id="accordionPanels${detail.id}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panels-collapse${detail.id}" aria-expanded="false" aria-controls="panels-collapse${detail.id}">
                                Nota ${dt.nomor_nota} Bersih  Rp. ${ribuanId(detail.bersih)} 
                             </button>
                        </h2>
                        <div id="panels-collapse${detail.id}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div>TP Sawit : ${detail.tp}, Pabrik Timbang Kotor : ${ribuanId(detail.timbang_kotor)} Kg, penyusutan : ${ribuanId(detail.nilai_susut)} Kg</div>
                                <div>Harga Sawit = ${ribuanId(detail.harga_sawit)}</div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="min-w-20px">No</th>
                                            <th class="min-w-400px">Item</th>
                                            <th class="min-w-100px">Biaya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Biaya Muat</td>
                                            <td>Rp. ${ribuanId(detail.biaya_loading)}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>biaya bongkar</td>
                                            <td>Rp. ${ribuanId(detail.biaya_bongkar)}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Sewa Mobil</td>
                                            <td>Rp. ${ribuanId(detail.sewa_mobil)}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Total Keluar</td>
                                            <td>Rp. ${ribuanId(detail.biaya_loading+detail.biaya_bongkar+detail.sewa_mobil)}</td>
                                        </tr>
                                        <tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary hapus-pabrik-berangkat" data-id="${dt.id}"><i class="bi bi-trash"></i></button>                            

                            </div>
                        </div>
                    </div>
                </div>`;
            }
    
        return bayarPabrik;
    }
    

    $('.tambah-baru').click(function(){
        $('#form-modal').trigger('reset');
        $('#form-modal input[type="hidden"]').val('');
        $('.body_muatan1').empty();
        showModal('modal-form');
    });

    var refreshAfterClose=false;
    $('#modal-form').on('hidden.bs.modal', function () {
        if (refreshAfterClose) {
            refreshData();
            refreshAfterClose = false; // Reset the flag
        }
    });    

    $.validator.addMethod("numberOnly", function(value, element) {
        return this.optional(element) || /^-?\d+(\.\d+)?$/.test(value);
    }, "Please enter a valid number");

    $.validator.addMethod("cekBeratPabrik", function(value, element) {
        let pabrik_timbang_kotor_akhir = $('.body_muatan2 tr#total_timbang').find('.pabrik_timbang_kotor').text();
        return value == pabrik_timbang_kotor_akhir;
    }, "Nilai akhir total berat pabrik tidak sama dengan hasil timbangan");

    $('#form-timbang-pabrik').validate({
        rules: {
            pabrik_timbang_kotor: {
                required: true,
                cekBeratPabrik: true
            }
        },
        messages: {
            pabrik_timbang_kotor: {
                required: "Tidak boleh kosong",
                cekBeratPabrik: "Nilai tidak sama"
            }
        },
        submitHandler: function(form, event) {
            let formData = $(form).serializeArray();
            let dataMuatan = [];
            //populasi mobil dan supir

            let berangkat_pabrik_id=$('#form-timbang-pabrik #berangkat_pabrik_id').val();
            
            $('.body_muatan2 tr.data').each(function() {
                var berangkat_timbang_id = $(this).data('berangkat_timbang_id');
                var persen = $(this).find('.persen').text();
                var pabrik_timbang_kotor = $(this).find('.number_pabrik_timbang_kotor').val();
                var harga = $(this).find('.number_harga').val();
               
                dataMuatan.push({
                    berangkat_timbang_id: berangkat_timbang_id,
                    persen: persen,
                    pabrik_timbang_kotor: pabrik_timbang_kotor,
                    pabrik_timbang_bersih: pabrik_timbang_kotor,
                    harga: harga,
                });
            });
            formData.push({ name: 'timbang_kotor', value: $("#pabrik_timbang_kotor").val() });
            formData.push({ name: 'timbang_bersih', value: $("#pabrik_timbang_bersih").val() });
            formData.push({ name: 'muatan', value: JSON.stringify(dataMuatan) });
            formData.push({ name: 'operator_id', value: auth_grup_akses.id });

            // console.log(formData);
            let type="post";
            let url=base_url+'/api/pabrik-berangkat';
            if(berangkat_pabrik_id){
                type="put";
                url=base_url+'/api/pabrik-berangkat/'+berangkat_pabrik_id;
            }

            saveData(url, type, formData, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                refreshData()
                hideModal('modal-timbang-pabrik');
            });
        }
    });

    $("#form-modal").validate({
        submitHandler: function(form, event) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? endpoint : endpoint + '/' + id;

            let formData = $(form).serializeArray();
            let dataMuatan = [];
            //populasi mobil dan supir
            $('.body_muatan1 tr').each(function() {
                var berangkat_timbang_id = $(this).data('berangkat_timbang_id');
                var ram_id = $(this).data('ram_id');
                var timbang_bersih = $(this).find('.kolom_timbang_bersih').text();
                var timbang_kotor = $(this).find('.kolom_timbang_kotor').text();
                dataMuatan.push({
                    berangkat_timbang_id: berangkat_timbang_id,
                    ram_id: ram_id,
                    ram_timbang_bersih: timbang_bersih,
                    ram_timbang_kotor: timbang_kotor
                });
            });
            formData.push({ name: 'muatan', value: JSON.stringify(dataMuatan) });
            formData.push({ name: 'operator_id', value: auth_grup_akses.id });

            saveData(url, type, formData, function(response) {
                // console.log(response);
                refreshAfterClose = true;
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                hideModal('modal-form');
                refreshData();
            });

        }        
    });

    $(document).on('click','.btn-ganti',function(){
        let id=$(this).data('id');
        $('#form-modal').trigger('reset');
        $('#form-modal input[type="hidden"]').val('');

        showDataById(endpoint, id, function(response) {
            // console.log(response);
            formGanti(response.data);
        });
    })

    $(document).on('click','.btn-hapus',function(){
        let id=$(this).data('id');
        deleteData(endpoint, id, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            refreshData();
        });
    })

    function tambahMuatan(elementId,ram_id,ram_nama,timbang_bersih,timbang_kotor,id="",boleh_hapus=false){
        var hapus="";
        if(boleh_hapus){
            hapus=`<button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary btn-hapus-mobil"><i class="bi bi-trash"></i></button>`;
        }

        var newRow = `<tr data-berangkat_timbang_id="${id}" data-ram_id="${ram_id}">
                        <td></td>
                        <td>${ram_nama}</td>
                        <td class="kolom_timbang_bersih">${timbang_bersih}</td>
                        <td class="kolom_timbang_kotor">${timbang_kotor}</td>
                        <td>${hapus}</td>
                    </tr>`;
        $(elementId).append(newRow);
        updateNomorUrut(elementId);
        // pilihSupir('#body_mobil_sopir tr:last .supir_id');

        $('#ram_id').val("").trigger('change');
        $("#timbang_bersih").val("");    
        $("#timbang_kotor").val("");    

    }

    function formGanti(data) {
        $('#id').val(data.id);
        $('#pabrik_id').val(data.pabrik_id).trigger('change');
        $('#supir_id').val(data.supir_id).trigger('change');
        $('#mobil_id').val(data.mobil_id).trigger('change');
        $('#tanggal').val(data.tanggal);

        $('.body_muatan1').empty();
        $.each(data.berangkat_timbang, function(index_muatan, dt_muatan) {
            let boleh_hapus=false;
            if(akses_id.operator==dt_muatan.operator_id)
                boleh_hapus=true;

            tambahMuatan(
                '.body_muatan1',
                dt_muatan.ram_id,
                dt_muatan.ram.nama,
                dt_muatan.ram_timbang_bersih,
                dt_muatan.ram_timbang_kotor,
                dt_muatan.id,
                boleh_hapus
            );
        });
        showModal('modal-form');
    }

    // untuk nota
    $(document).on('click', '.bayar-pabrik', function() {
        var id = $(this).data('id');
        showBayarPabrikModal(id);
    });

    function showBayarPabrikModal(id) {
        $('#form-modal-pabrik').trigger('reset');
        $('#form-modal-pabrik input[type="hidden"]').val('');
        $('#berangkat_timbang_id').val(id);
        fetchData(endpoint+'/'+id, function(response) {
            let dt=response.data.berangkat_pabrik;
            if(dt.id){
                // console.log(dt);
                let form = $('#form-modal-pabrik');
                form.find('#id').val(dt.id);
                form.find('#tanggal').val(dt.tanggal);
                form.find('#timbang_kotor').val(dt.timbang_kotor);
                form.find('#timbang_bersih').val(dt.timbang_bersih);
                form.find('#harga_sawit').val(dt.harga_sawit);
                form.find('#sewa_supir').val(dt.sewa_supir);
                form.find('#sewa_mobil').val(dt.sewa_mobil);
                form.find('#biaya_muat').val(dt.biaya_muat);
                form.find('#biaya_bongkar').val(dt.biaya_bongkar);    
            }
        }); 
        showModal('modal-form-pabrik');
        // Tampilkan modal
    }    


    $("#form-modal-pabrik").validate({
        submitHandler: function(form, event) {
            const id = $(form).find('#id').val();
            const type = (id == '') ? 'POST' : 'PUT';
            let url = base_url+'/api/pabrik-berangkat';
            url = (id == '') ? url : url + '/' + id;
            let formData = $(form).serializeArray();

            function getValue(name) {
                let field = formData.find(item => item.name === name);
                return field ? parseFloat(field.value.trim()) || 0 : 0;
            }
    
            let vharga_sawit = getValue('harga_sawit');
            let vtimbang_bersih = getValue('timbang_bersih');
            let vsewa_supir = getValue('sewa_supir');
            let vsewa_mobil = getValue('sewa_mobil');
            let vbiaya_muat = getValue('biaya_muat');
            let vbiaya_bongkar = getValue('biaya_bongkar');

            let vtotal_sawit = vharga_sawit * vtimbang_bersih;
            let vtotal_keluar = (vsewa_supir + vsewa_mobil + vbiaya_muat + vbiaya_bongkar);
            let vtotal_masuk = vtotal_sawit - vtotal_keluar;
            
            formData.push({ name: 'operator_id', value: auth_grup_akses.id });
            formData.push({ name: 'total_sawit', value: vtotal_sawit });
            formData.push({ name: 'total_keluar', value: vtotal_keluar });
            formData.push({ name: 'total_masuk', value: vtotal_masuk });
            
            // console.log(formData);
            if(confirm('yakin semua data sudah benar ? coba cek lagi...'))
                saveData(url, type, formData, function(response) {
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    refreshData();
                });
        }        
    });    

});
