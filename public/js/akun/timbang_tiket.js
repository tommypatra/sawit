// console.log(akses_id);
$(document).ready(function() {
    var user_grup_akses = 'operator';
    tokenCek(user_grup_akses);
    var auth_grup_akses=cariGrupAkses(user_grup_akses);

    var endpoint=base_url+'/api/timbang-tiket';
    var activeTab = 'masuk-tab';

    refreshData();
    dataSumberBayar()
    dataPelanggan();

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        activeTab = $('.nav-tabs .nav-link.active').attr('id');
        if (activeTab === 'masuk-tab') {
            refreshData();
        }
    });

    function refreshData(){
        dataLoad();    
    }

    $('#search-input').on('keypress', function(event) {
        if (event.which === 13 && activeTab === 'masuk-tab') {
            dataLoad();            
        }
    });    

    $('.refresh-data').click(function(){
        let activeTab = $('.nav-tabs .nav-link.active').attr('id');
        if (activeTab === 'masuk-tab') {
            refreshData();
        }
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

    function dataPelanggan() {
        var url = base_url + '/api/pelanggan';
        fetchData(url, function(response) {
            $('#pelanggan_id').empty();
            $.each(response.data, function(key, value) {
                $('#pelanggan_id').append($('<option>', {
                    value: value.pelanggan_id,
                    text: value.user_name,
                    email: value.user_email,
                }));
            });
            $('#pelanggan_id').trigger('change');
            $('#pelanggan_id').select2({
                dropdownParent: $('#modal-form'),
                templateResult: formatDataSelect2,
            });    
        });
    }

    function dataSumberBayar() {
        var url = base_url + '/api/sumber-bayar';
        fetchData(url, function(response) {
            $('#sumber_bayar_id').empty();
            $.each(response.data, function(key, value) {
                $('#sumber_bayar_id').append($('<option>', {
                    value: value.id,
                    text: value.nama,
                }));
            });
            $('#sumber_bayar_id').trigger('change');
            $('#sumber_bayar_id').select2({
                dropdownParent: $('#modal-form-nota'),
            });    
        });
    }    
    
    function formatDataSelect2(data) {
        if (!data.id) {
            return data.text;
        }
        var email = $(data.element).attr('email');
        var email = $('<span>', { style: 'font-size: 13px; color: #888' }).text(email);
        var wrapper = $('<div>').append(data.text).append('<br>').append(email);
        return wrapper;
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
                let linkfile=(dt.file!==null)?dt.file:'';
                if(linkfile){
                    const tmp = `${base_url}/${linkfile}`;
                    const content = (dt.file_jenis === 'img') ? `<img src="${tmp}" height="100px">` : 'Dokumen';
                    linkfile = `<a href="${tmp}" target="_blank">${content}</a>`;
                }

                const row = `<tr>
                            <td>
                                <input class="form-check-input cek-baris" type="checkbox"  
                                    data-timbang_bersih="${dt.timbang_bersih}" 
                                    data-pelanggan_id="${dt.pelanggan_id}" 
                                    data-pelanggan_user_name="${dt.pelanggan_user_name}"
                                    value="${dt.id}">
                            </td>
                            <td>${no++}</td>
                            <td>
                                <figure>
                                    <blockquote class="blockquote">
                                        <p>${dt.pelanggan_user_name}</p>
                                    </blockquote>
                                    <figcaption class="blockquote-footer">${dt.pelanggan_user_alamat} (${dt.pelanggan_user_hp})</figcaption>
                                </figure>                            
                            </td>
                            <td>${dt.timbang_bersih} Kg</td>
                            <td>${linkfile}</td>
                            <td>${dt.tanggal}</td>
                            <td>${dt.operator_user_name}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-ganti" data-id="${dt.id}" data-action="edit" href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
                                        <li><a class="dropdown-item btn-hapus" data-id="${dt.id}" data-action="delete" href="javascript:;"><i class="bi bi-trash"></i> Hapus</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>`;
                dataList.append(row);
            });
            renderPagination(response, pagination);
        }else {
            const row = `<tr><td colspan="8">Tidak Ditemukan</td></tr>`;
            dataList.append(row);
        }
    }

    // Event handler for .btn-bayarkan click
    $(document).on('click', '.btn-bayarkan', function() {
        var id = $(this).data('id');
        $('input.cek-baris[value="' + id + '"]').prop('checked', true);
        $('.buatkan-nota').click();
    });

    $('.tambah-baru').click(function(){
        $('#form-modal').trigger('reset');
        $('#form-modal input[type="hidden"]').val('');
        showModal('modal-form');
    });

    $.validator.addMethod("numberOnly", function(value, element) {
        return this.optional(element) || /^-?\d+(\.\d+)?$/.test(value);
    }, "Please enter a valid number");

    $("#form-modal").validate({
        rules: {
            timbang_bersih: {
                required: true,
                numberOnly: true
            }
        },
        submitHandler: function(form, event) {
            const id = $('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? endpoint : endpoint + '/' + id;
            var formData = new FormData(form);
            formData.append('_method', type);
            formData.append('operator_id', auth_grup_akses.id);
            if(confirm('yakin semua data sudah benar ? coba cek lagi...'))
                saveData(url, 'POST', formData, function(response) {
                    $('#form').trigger('reset');
                    $('#form input[type="hidden"]').val('');
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
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

    function formGanti(data) {
        $('#id').val(data.id);
        $('#tanggal').val(data.tanggal);
        $('#timbang_bersih').val(data.timbang_bersih);
        $('#pelanggan_id').val(data.pelanggan_id).trigger('change');
        showModal('modal-form');
    }

    // untuk nota
    $(document).on('click','.buatkan-nota', function() {
        let selectedRows = [];
        $('.cek-baris:checked').each(function() {
            let row = $(this).closest('tr');
            let data = {
                pelanggan_id: $(this).data('pelanggan_id'),
                timbang_tiket_id: $(this).val(),
                pelanggan_user_name: $(this).data('pelanggan_user_name'),
                timbang_bersih: $(this).data('timbang_bersih'),
                tanggal: row.find('td:nth-child(6)').text(),
                operator: row.find('td:nth-child(7)').text()
                // tambahkan data lain yang diperlukan
            };
            selectedRows.push(data);
        });
        if(selectedRows.length>0)
            showNotaModal(selectedRows);
        else
            toastr.error('tidak ada data terpilih!', 'gagal');
    });

    function showNotaModal(data) {
        let notaList = $('#notaList');
        notaList.empty();
        $('#total-keseluruhan-angka').text('Rp. 0');
        $('#total-keseluruhan-terbilang').text('rupiah');

        $.each(data, function(index, item) {
            let rowHtml = `
                <div class="row daftar-nota" data-timbang_tiket_id="${item.timbang_tiket_id}" data-pelanggan_id="${item.pelanggan_id}">
                    <div class="col-md-1">
                        ${index+1}.
                    </div>
                    <div class="col-md-6">
                        <label>${item.pelanggan_user_name}</label>
                        <div><i class="bi bi-calendar2-event"></i> ${item.tanggal}</div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <span class="jumlah_satuan">${item.timbang_bersih}</span> Kg
                            </div>
                            <div class="col-md-5 mb-2">
                                <input type="number" width="200px" class="form-control harga_satuan" placeholder="Harga Satuan" value="0" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                Rp. <span class="total_bayar">0</span>
                            </input>
                        </div>
                    </td>
                </tr>`;
            notaList.append(rowHtml);
        });

        // Tampilkan modal
        $('#form-modal-nota').trigger('reset');
        $('#form-modal-nota input[type="hidden"]').val('');
        showModal('modal-form-nota');
    }    

    // Bind event listener for harga_satuan change
    $(document).on('input','.harga_satuan', function() {
        let daftar = $(this).closest('.daftar-nota');
        let jumlahSatuan = parseInt(daftar.find('.jumlah_satuan').text());
        let hargaSatuan = parseInt($(this).val());
        let totalBayar = jumlahSatuan * hargaSatuan; 

        daftar.find('.total_bayar').text(formatRupiah(totalBayar));

        // totalBayar.text(jumlahSatuan * hargaSatuan);

        updateTotalKeseluruhan();
    });

    // Function to update total keseluruhan
    function updateTotalKeseluruhan() {
        let totalKeseluruhan = 0;
        $('.total_bayar').each(function() {
            let angkabayar=$(this).text().replace(/\./g, '');
            totalKeseluruhan += parseInt(angkabayar);
            // console.log(angkabayar);
        });
        $('#total-keseluruhan-angka').text('Rp. '+formatRupiah(totalKeseluruhan));
        $('#total-keseluruhan-terbilang').text(terbilang(totalKeseluruhan)+' rupiah');
    }    

    $("#form-modal-nota").validate({
        submitHandler: function(form, event) {
            let formData = $(form).serializeArray();
            formData.push({ name: 'operator_id', value: auth_grup_akses.id });

            let additionalData = [];

            // Collecting values from .daftar-nota rows
            $('.daftar-nota').each(function() {
                let pelanggan_id = $(this).data('pelanggan_id');
                let timbang_tiket_id = $(this).data('timbang_tiket_id');
                let harga_satuan = $(this).find('.harga_satuan').val();
                let jumlah_satuan = $(this).find('.jumlah_satuan').text();
                let total_bayar = harga_satuan*jumlah_satuan;

                additionalData.push({
                    name: 'timbang_tiket_id[]',
                    value: timbang_tiket_id
                });
                additionalData.push({
                    name: 'pelanggan_id[]',
                    value: pelanggan_id
                });
                additionalData.push({
                    name: 'harga_satuan[]',
                    value: harga_satuan
                });
                additionalData.push({
                    name: 'jumlah_satuan[]',
                    value: jumlah_satuan
                });
                additionalData.push({
                    name: 'total_bayar[]',
                    value: total_bayar
                });
            });

            // Combine formData and additionalData
            formData = formData.concat(additionalData);
            // console.log(formData);
            if(confirm('yakin semua data sudah benar ? coba cek lagi...'))
                saveData(base_url+'/api/timbang-beli', 'POST', formData, function(response) {
                    toastr.success('operasi berhasil dilakukan!', 'berhasil');
                    refreshData();
                });
        }        
    });    

});
