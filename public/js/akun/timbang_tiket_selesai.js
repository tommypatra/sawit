// console.log(akses_id);
$(document).ready(function() {
    // let user_grup_akses = 'operator';
    // tokenCek(user_grup_akses);
    // let auth_user_id=cariGrupAkses(user_grup_akses);

    var endpoint=base_url+'/api/timbang-nota';
    var activeTab = '';

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        activeTab = $('.nav-tabs .nav-link.active').attr('id');
        if (activeTab === 'nota-tab') {
            dataLoad();
        }
    });    

    $('#search-input').on('keypress', function(event) {
        if (event.which === 13 && activeTab === 'nota-tab') {
            dataLoad();            
        }
    });    

    $('.refresh-data').click(function(){
        let activeTab = $('.nav-tabs .nav-link.active').attr('id');
        if (activeTab === 'nota-tab') {
            dataLoad();
        }
    });

    function dataLoad(page = 1) {
        var search = $('#search-input').val();
        var url = endpoint + '?page=' + page + '&search=' + search + '&limit=' + vLimit;
        fetchData(url, function(response) {
            // console.log(response);
            renderData(response);
        },true);
    }

    $(document).on('click', '#pagination-nota .page-link', function() {
        var page = $(this).data('page');
        var search = $('#search-input').val();
        dataLoad(page, search);
    });    
    
    //untuk render dari dataLoad
    function renderData(response) {
        const dataList = $('#data-list-nota');
        const pagination = $('#pagination-nota');
        let no = (response.current_page - 1) * response.per_page + 1;
        dataList.empty();
        if (response.data.length > 0) {
            $.each(response.data, function(index, dt) {
                const rincian = rincianNota(dt.id,dt.nomor_nota,dt.detail);
                const row = `<tr>
                                <td>${no++}</td>
                                <td>
                                    <h4>${dt.nama}</h4>
                                    <div class="fs-9">${dt.waktu}</div>
                                    <div>
                                        ${rincian}
                                    </div>
                                </td>
                                <td>${dt.timbang_beli_total_berat} Kg</td>
                                <td>
                                    <div>Rp. ${ribuanId(dt.timbang_beli_total_bayar)}</div>
                                    <div>${dt.jenis_bayar} - ${dt.sumber_bayar_nama}</div>
                                </td>
                                <td>Rp. ${dt.biaya_transfer.toLocaleString('id-ID')}</td>
                                <td>${dt.operator_user_name}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item btn-cetak-nota" data-id="${dt.id}" data-action="cetak-nota" href="javascript:;"><i class="bi bi-printer"></i> Cetak Nota</a></li>
                                            <li><a class="dropdown-item btn-hapus-nota" data-id="${dt.id}" data-action="delete-nota" href="javascript:;"><i class="bi bi-trash"></i> Hapus</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>`;
                dataList.append(row);
            });
            renderPagination(response, pagination);
        }else {
            const row = `<tr><td colspan="7">Tidak Ditemukan</td></tr>`;
            dataList.append(row);
        }
    }

    function rincianNota(id, no_nota, detail) {
        function createTableRows(detail) {
            return detail.map((dt, index) => {
                return `
                    <tr>
                        <td>${index + 1}.</td>
                        <td>${dt.timbang_tiket.pelanggan.user.name}</td>
                        <td>${dt.jumlah_satuan} Kg x Rp. ${ribuanId(dt.harga_satuan)}</td>
                        <td>Rp. ${ribuanId(dt.total_bayar)}</td>
                    </tr>`;
            }).join('');
        }
        const tableRows = createTableRows(detail);
        const nota = `
            <div class="accordion" id="accordionPanels${id}">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panels-collapse${id}" aria-expanded="false" aria-controls="panels-collapse${id}">
                            Detail Nota ${no_nota} (${detail.length})
                        </button>
                    </h2>
                    <div id="panels-collapse${id}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="min-w-20px">No</th>
                                        <th class="min-w-150px">Nama</th>
                                        <th class="min-w-150px">Satuan x Harga</th>
                                        <th class="min-w-150px">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${tableRows}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`;
    
        return nota;
    }
    

    $(document).on('click','.btn-hapus-nota',function(){
        let id=$(this).data('id');
        deleteData(endpoint, id, function(response) {
            toastr.success('hapus data berhasil dilakukan!', 'berhasil');
            dataLoad();
        });
    });

});
