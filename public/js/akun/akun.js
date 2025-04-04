var user_grup_akses = 'admin';
var page;
tokenCek(user_grup_akses);
var auth_grup_akses=cariGrupAkses(user_grup_akses);
var endpoint=base_url+'/api/user';

$(document).ready(function() {
    refreshData();

    $.ajax({
        url: 'api/grup', // Ganti dengan URL API
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let checkboxList = $('#daftar-akses');
            checkboxList.empty(); // Kosongkan daftar sebelum diisi
            $.each(response.data, function(index, item) {
                let listItem = `
                    <div>
                        <input type="checkbox" id="akses_${item.id}" data-nama="${item.nama}" name="akses[]" value="${item.id}" />
                        <label for="akses_${item.id}">${item.nama}</label>
                    </div>
                `;
                checkboxList.append(listItem);
            });
        },
        error: function(xhr, status, error) {
            console.error("Gagal mengambil data:", error);
        }
    });

    function daftarAkses(id){
        $.ajax({
            url: 'api/daftar-akses/'+id, // Ganti dengan URL API
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#daftar-akses input[type="checkbox"]').prop('checked', false).prop('disabled', false);
                $.each(response.daftar, function(index, item) {
                    $(`#daftar-akses input[value="${item.grup_id}"]`).prop('checked', true).prop('disabled', true);
                });
            },
            error: function(xhr, status, error) {
                console.error("Gagal mengambil data:", error);
            }
        });        
    }

    function refreshData(){
        dataLoad();
    }

    $('.refresh-data').click(function(){
        refreshData();
    });

    function dataLoad(vPage) {
        page = (vPage)?vPage:1;
        var search = $('#search-input').val();
        var grup = $('.nav-tabs .nav-link.active').attr('data-value');
        var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            renderData(response);
        },true);
    }

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

                let akses = [];
            
                dt.supir && akses.push({ id: dt.supir.id, grup_id:dt.supir.grup_id, akses: 'supir' });
                dt.admin && akses.push({ id: dt.admin.id, grup_id:dt.admin.grup_id, akses: 'admin' });
                dt.operator && akses.push({ id: dt.operator.id, grup_id:dt.operator.grup_id, akses: 'operator' });
                dt.pelanggan && akses.push({ id: dt.pelanggan.id, grup_id:dt.pelanggan.grup_id, akses: 'pelanggan' });
                dt.user_pabrik && akses.push({ id: dt.user_pabrik.id, grup_id:dt.user_pabrik.grup_id, akses: 'pabrik' });
            
                // Buat daftar akses sebagai string HTML
                let akses_list="";
                if(akses.length>0){
                    akses_list = '<ul>';
                    akses.forEach(item => {
                        akses_list += ` <li>
                                            ${item.akses} 
                                            <a href="javascript:;" class="hapus-akses" data-id="${item.id}" data-grup_id="${item.grup_id}" data-akses="${item.akses}"><i class="bi bi-trash"></i></a>
                                        </li>`;
                    });
                    akses_list += '</ul>';
                }
                
                const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.name}</td>
                                <td>${dt.email}</td>
                                <td>${akses_list}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
                                            <li><a class="dropdown-item btn-akses" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-person-check"></i> Akses Akun</a></li>
                                            <li><a class="dropdown-item btn-hapus" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-trash"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            `;
                dataList.append(row);
            });
            renderPagination(response, pagination);
        }else {
            const row = `<tr><td colspan="5">Tidak Ditemukan</td></tr>`;
            dataList.append(row);
        }
    }

    $('.tambah-baru').click(function(){
        $('#form-modal').trigger('reset');
        $('#form-modal input[type="hidden"]').val('');
        showModal('modal-form');
    });

    $('#form-modal').validate({
        rules: {
            password: {
                required: function() {
                    return $('#form-modal #id').val().trim() === ''; // Password wajib jika ID kosong
                },
                minlength: 8 // Password minimal 8 karakter
            }
        },
        messages: {
            password: {
                required: "Password wajib diisi",
                minlength: "Password minimal 8 karakter"
            }
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            const $form = $(form); // Konversi ke jQuery object
            const id = $form.find('#id').val();
            const type = (id === '') ? 'POST' : 'PUT';
            const url = (id === '') ? endpoint : endpoint + '/' + id;

            let formData = $form.serializeArray();
            if(id !== '')
                formData.push({ name: '_method', value: type });

            saveData(url, type, formData, function(response) {
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
            formGanti(response.data);
            showModal('modal-form');
        });
    })

    $(document).on('click','.btn-hapus',function(){
        let id=$(this).data('id');
        deleteData(endpoint, id, function(response) {
            toastr.success('operasi berhasil dilakukan!', 'berhasil');
            refreshData();
        });
    })

    $(document).on('click', '.hapus-akses', function () {
        let id = $(this).data('id');
        let akses = $(this).data('akses');
        let grup_id = $(this).data('grup_id');
    
        if (!id || !akses) {
            alert("Data tidak valid!");
            return;
        }
    
        if (confirm("Apakah anda yakin ingin menghapus akses ini?")) {
            $.ajax({
                url: `api/hapus-akses-user?id=${id}&akses=${akses}&grup_id=${grup_id}`,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    refreshData();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Terjadi kesalahan saat menghapus akses!");
                }
            });
        }
    });

    function formGanti(data) {
        const form=$('#form-modal');
        form.find('#id').val(data.id);
        form.find('#name').val(data.name);
        form.find('#alamat').val(data.alamat);
        form.find('#hp').val(data.hp);
        form.find('#email').val(data.email);
    }

    $(document).on('click','.btn-akses',function(){
        const id=$(this).data('id');
        daftarAkses(id);
        $('#form-akses').trigger('reset');
        $('#form-akses #user_id').val(id);
        showModal('modal-akses');

    });

    $('#form-akses').on('submit', function (e) {
        e.preventDefault(); // Hindari reload halaman
        let user_id = $('#form-akses #user_id').val();
        let aksesData = [];    
        $('#daftar-akses input[type="checkbox"]:checked').each(function () {
            if(!$(this).prop('disabled')){
                let grupId = $(this).val();
                let grupNama = $(this).data('nama');
                aksesData.push({
                    grup_id: grupId,
                    grup_nama: grupNama
                });
            }
        });
        
        $.ajax({
            url: 'api/simpan-akses-user', // Endpoint untuk menyimpan akses
            type: 'POST',
            data: JSON.stringify({ user_id:user_id, akses: aksesData }),
            dataType: 'json',
            contentType: 'application/json', // Pastikan dikirim dalam JSON
           
            success: function (response) {
                daftarAkses(user_id);
                refreshData();
            },
            error: function (xhr, status, error) {
                console.error("Gagal menyimpan data:", error);
            }
        });
    });    

});
