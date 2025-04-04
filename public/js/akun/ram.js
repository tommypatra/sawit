var user_grup_akses = 'admin';
var page;
tokenCek(user_grup_akses);
var auth_grup_akses=cariGrupAkses(user_grup_akses);
var endpoint=base_url+'/api/ram';

$(document).ready(function() {
    refreshData();

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
                const aktif = (dt.is_aktif)?'Aktif':'Tidak Aktif';
                const row = `<tr>
                                <td>${no++}</td>
                                <td>${dt.nama}</td>
                                <td>${dt.alamat}</td>
                                <td>${aktif}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-ganti" data-id="${dt.id}"  href="javascript:;"><i class="bi bi-pencil-square"></i> Ganti</a></li>
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


    function formGanti(data) {
        const form=$('#form-modal');
        form.find('#id').val(data.id);
        form.find('#nama').val(data.nama);
        form.find('#alamat').val(data.alamat);
        form.find('#is_aktif').val(data.is_aktif);
    }


});
