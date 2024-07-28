// console.log(akses_id);
$(document).ready(function() {
    var user_grup_akses = 'operator';
    tokenCek(user_grup_akses);
    var auth_grup_akses=cariGrupAkses(user_grup_akses);
    var endpoint=base_url+'/api/timbang-berangkat';

    refreshData();
    dataPabrik();
    dataMobil();
    dataSupir();

    function refreshData(){
        dataLoad();    
    }

    $('#search-input').on('keypress', function(event) {
        if (event.which === 13) {
            dataLoad();            
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
    
    var supirData=[];
    function dataSupir() {
        var url = base_url + '/api/supir';
        fetchData(url, function(response) {
            supirData=response.data;
        });
    }

    function dataMobil() {
        var url = base_url + '/api/mobil';
        var selectElement='#mobil_id';

        $(selectElement).empty();
        const option_def = `<option value="">- pilih mobil -</option>`;
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

    function dataLoad(page = 1) {
        var search = $('#search-input').val();
        var url = endpoint + '?masuk=1&page=' + page + '&search=' + search + '&limit=' + vLimit;
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

                let mobil_supir="";
                if(dt.berangkat_mobil.length>0){
                    mobil_supir="<ul>";
                    $.each(dt.berangkat_mobil, function(index_mobil, dt_mobil) {
                        mobil_supir+=`<li>${dt_mobil.mobil.nama} (${dt_mobil.mobil.no_polisi})</li>`;
                        
                        if(dt_mobil.berangkat_supir.length>0){
                            mobil_supir+="<ul>";
                            $.each(dt_mobil.berangkat_supir, function(index_supir, dt_supir) {
                                // console.log(dt_supir);
                                mobil_supir+=`<li>${dt_supir.supir.user.name}</li>`;
                            });
                            mobil_supir+="</ul>";
                        }
        
                    });
                    mobil_supir+="</ul>";
                }
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
                                <td>${dt.timbang_bersih} Kg/ ${dt.timbang_kotor} Kg</td>
                                <td>${mobil_supir}</td>
                                <td>${dt.operator_user_name} / ${dt.tanggal}</td>
                                <td>Pembayaran</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item bayar-pabrik" data-id="${dt.id}" href="javascript:;"><i class="bi bi-wallet2"></i> Bayar Pabrik</a></li>
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
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
    function updateNomorUrut() {
        $('#body_mobil_sopir tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    // untuk memasukan supir dalam select2    
    function pilihSupir(selectElement) {
        $(selectElement).empty();
        const option_def = `<option value="">- pilih supir -</option>`;
        $(selectElement).append(option_def);
        
        supirData.forEach(function(supir) {
            const option = `<option value="${supir.id}">${supir.nama} (${supir.email})</option>`;
            $(selectElement).append(option);
        });

        $(selectElement).select2({
            // dropdownParent: $('#modal-form'),
        });    
    }

    $('#tambah_mobil').click(function(){
        var mobil_id = $("#mobil_id").val();
        var mobil_text = $("#mobil_id").find('option:selected').text();    
        if(mobil_id){    
            tambahBarisMobil(mobil_id,mobil_text);
            updateNomorUrut();
            pilihSupir('#body_mobil_sopir tr:last .supir_id');
        }
    });


    $(document).on('click', '.btn-hapus-mobil', function() {
        var dtmobil = $(this).closest('tr'); 
        var id = dtmobil.data('berangkat_mobil_id');
        if(!id)
            dtmobil.remove();
        else{
            deleteData(base_url+'/api/mobil-berangkat', id, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                refreshData();
                dtmobil.remove();
            });                        
        }
        updateNomorUrut();
    });   
    
    $(document).on('click', '.tambah-supir', function() {
        var row = $(this).closest('tr');
        var select = row.find('.supir_id');
        var supir_id = select.val();
        var supir_text = select.find('option:selected').text();

        if (supir_id!=='') {
            var $daftarSupir = row.find('.daftar-supir');
            if ($daftarSupir.text().trim() === 'belum ada supir') {
                $daftarSupir.empty();
            }
            var supirItem = `<li data-berangkat_supir_id  data-supir_id="${supir_id}">${supir_text} <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary btn-hapus-supir"><i class="bi bi-trash"></i></button></li>`;
            $daftarSupir.append(supirItem);
        }
    });

    $(document).on('click', '.btn-hapus-supir', function() {
        var dtsupir = $(this).closest('li'); // Simpan referensi elemen li
        var id = dtsupir.data('berangkat_supir_id');
        if(!id)
            dtsupir.remove();
        else{
            deleteData(base_url+'/api/supir-berangkat', id, function(response) {
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                refreshData();
                dtsupir.remove();
            });                        
        }
    });


    function rincianPabik(dt) {
        let detail=dt.berangkat_pabrik;
        let bayarPabrik='';
        if(detail!==null){
            bayarPabrik = `
                <div class="accordion" id="accordionPanels${detail.id}">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panels-collapse${detail.id}" aria-expanded="false" aria-controls="panels-collapse${detail.id}">
                                Transaksi ${dt.pabrik_nama}, Nota ${dt.nomor_nota}
                            </button>
                        </h2>
                        <div id="panels-collapse${detail.id}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div>Timbang Kotor : ${ribuanId(detail.timbang_kotor)} Kg, Timbang Bersih : ${ribuanId(detail.timbang_bersih)} Kg</div>
                                <div>Masuk = ${ribuanId(detail.timbang_bersih)} Kg x Rp. ${ribuanId(detail.harga_sawit)} = Rp. ${ribuanId(detail.timbang_bersih*detail.harga_sawit)}</div>
                                <table class="tabele">
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
                                            <td>Rp. ${ribuanId(detail.biaya_muat)}</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>biaya_bongkar</td>
                                            <td>Rp. ${ribuanId(detail.biaya_bongkar)}</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Sewa Mobil</td>
                                            <td>Rp. ${ribuanId(detail.sewa_mobil)}</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Sewa Supir</td>
                                            <td>Rp. ${ribuanId(detail.sewa_supir)}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Rp. ${ribuanId(detail.total_keluar)}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
        $('#body_mobil_sopir').empty();
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

    $("#form-modal").validate({
        rules: {
            timbang_bersih: {
                required: true,
                numberOnly: true
            },
            timbang_kotor: {
                required: true,
                numberOnly: true
            }
        },
        submitHandler: function(form, event) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? endpoint : endpoint + '/' + id;

            let formData = $(form).serializeArray();
            let mobilData = [];
            //populasi mobil dan supir
            $('#body_mobil_sopir tr').each(function() {
                var mobil_id = $(this).data('mobil_id');
                var berangkat_mobil_id = $(this).data('berangkat_mobil_id');
                var supir_list = [];
                $(this).find('.daftar-supir li').each(function() {
                    supir_list.push({berangkat_supir_id:$(this).data('berangkat_supir_id'),supir_id:$(this).data('supir_id')});
                });
                mobilData.push({
                    mobil_id: mobil_id,
                    berangkat_mobil_id: berangkat_mobil_id,
                    supir_list: supir_list
                });
            });
            formData.push({ name: 'mobil', value: JSON.stringify(mobilData) });
            formData.push({ name: 'operator_id', value: auth_grup_akses.id });


            saveData(url, type, formData, function(response) {
                // console.log(response);
                refreshAfterClose = true;
                toastr.success('operasi berhasil dilakukan!', 'berhasil');
                hideModal('modal-form');
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

    function tambahBarisMobil(mobil_id,mobil_text,berangkat_mobil_id='',dataSupir=[]){
        var supir="";
        if(dataSupir.length>0){
            $.each(dataSupir, function(i, dt) {
                supir += `<li data-berangkat_supir_id="${dt.id}"  data-supir_id="${dt.supir_id}">${dt.supir.user.name} (${dt.supir.user.email}) <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary btn-hapus-supir"><i class="bi bi-trash"></i></button></li>`;
            });
        }
        
        var newRow = `<tr data-berangkat_mobil_id="${berangkat_mobil_id}" data-mobil_id="${mobil_id}">
                        <td></td>
                        <td>${mobil_text}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-select supir_id"></select>
                                </div>
                                <div class="col-md-4">                            
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary tambah-supir"><i class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                            <ul class="daftar-supir">
                                ${supir}
                            </ul>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm btn-hapus-mobil"><i class="bi bi-trash"></i></button></td>
                    </tr>`;
        $('#body_mobil_sopir').append(newRow);
        updateNomorUrut();
        pilihSupir('#body_mobil_sopir tr:last .supir_id');
    }

    function formGanti(data) {
        $('#id').val(data.id);
        $('#pabrik_id').val(data.pabrik_id).trigger('change');
        $('#tanggal').val(data.tanggal);
        $('#timbang_bersih').val(data.timbang_bersih);
        $('#timbang_kotor').val(data.timbang_kotor);
        // $('#pelanggan_id').val(data.pelanggan_id).trigger('change');

        $('#body_mobil_sopir').empty();
        $.each(data.berangkat_mobil, function(index_mobil, dt_mobil) {
            tambahBarisMobil(
                dt_mobil.mobil_id,
                `${dt_mobil.mobil.nama} (${dt_mobil.mobil.no_polisi})`,
                dt_mobil.id,
                dt_mobil.berangkat_supir
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
                console.log(dt);
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
