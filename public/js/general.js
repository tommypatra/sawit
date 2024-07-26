var is_login = (localStorage.getItem('access_token'))?true:false;
var akses_grup = (localStorage.getItem('akses_grup_id'))?localStorage.getItem('akses_grup_id'):'';
var akses_id = (localStorage.getItem('akses_id')) ? JSON.parse(localStorage.getItem('akses_id')) : [];
var daftar_akses = (localStorage.getItem('daftar_akses')) ? JSON.parse(localStorage.getItem('daftar_akses')) : [];

$(document).ready(function() {
    //untuk title
    var metaTitleContent = $('meta[name="title"]').attr('content');
    var currentTitle = $('title').text();
    if(metaTitleContent!== undefined)
        $('title').text(currentTitle + " - " + metaTitleContent);

    //untuk menu
    if (is_login === true) {
        $('.nav-user-login').css('display', 'block');
        if (Array.isArray(daftar_akses)) {
            daftar_akses.forEach(function(grup) {
                if (grup.nama.toLowerCase() === 'operator') {
                    $('.menu-operator').css('display', 'block');
                } else if (grup.nama.toLowerCase() === 'supir') {
                    $('.menu-supir').css('display', 'block');
                } else if (grup.nama.toLowerCase() === 'pabrik') {
                    $('.menu-pabrik').css('display', 'block');
                } else if (grup.nama.toLowerCase() === 'pelanggan') {
                    $('.menu-pelanggan').css('display', 'block');
                } else if (grup.nama.toLowerCase() === 'admin') {
                    $('.menu-admin').css('display', 'block');
                }
            });
        }
    }else{
        $('.nav-user-login').css('display', 'none');
        $('.menu-operator').css('display', 'none');
        $('.menu-supir').css('display', 'none');
        $('.menu-pabrik').css('display', 'none');
        $('.menu-pelanggan').css('display', 'none');
        $('.menu-admin').css('display', 'none');        
    }
});

function showModal(id) {
    var modalElement = document.getElementById(id);
    if (!modalElement) {
        console.error(`Element with id "${id}" not found.`);
        return;
    }
    var modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });
    modal.show();
}

function hideModal(id) {
    var modalElement = document.getElementById(id);
    if (!modalElement) {
        console.error(`Element with id "${id}" not found.`);
        return;
    }

    var modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
        modal.hide();
    } else {
        console.error(`No modal instance found for element with id "${id}".`);
    }
}


function myFormatDate(dateString){
    var date = new Date(dateString);
    // Periksa apakah objek Date valid
    if (isNaN(date.getTime())) {
        return 'Invalid Date';
    }    
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function myLabel(tmpVar){
    var tmp='';
    if (tmpVar!==null) {
        tmp=tmpVar;
    }
    return tmp;
}

function ajaxRequest(url, method, data=null, is_show=false, successCallback, errorCallback) {
    var hasFile = false;

    if (data instanceof FormData) {
        data.forEach(function(value, key) {
            if (value instanceof File) {
                hasFile = true;
            }
        });
    }

    var modalElement = document.getElementById('loading-modal');
    var modal = new bootstrap.Modal(modalElement, {
        keyboard: false
    });
    
    if(is_show){
        modal.show();
    }

    var ajaxOptions = {
        url: url,
        type: method,
        success: function(response) {
            if (successCallback) {
                successCallback(response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401 && errorThrown === "Unauthorized") {
                forceLogout('Mohon login kembali');
            } else {
                if (jqXHR.status === 422) {
                    const errors = jqXHR.responseJSON.errors;
                    $.each(errors, function(index, dt) {
                        alert(dt);
                    });
                } else {
                    toastr.error(jqXHR.responseJSON.message, 'terjadi kesalahan');

                }
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
            if (errorCallback) {
                errorCallback(jqXHR, textStatus, errorThrown);
            }
        }
    };

    if (data !== null) {
        ajaxOptions.data = data;
    }

    if (hasFile) {
        ajaxOptions.contentType = false;
        ajaxOptions.processData = false;
    }
    
    $.ajax(ajaxOptions);

    if(is_show){
        modalElement.addEventListener('shown.bs.modal', function () {
            modal.hide();
        });      
    }
}


function renderSelect(elm,opt,id=null){
    const select = $(elm);
    select.empty();
    let no = 1;
    $.each(opt, function(index, dt) {
        var selected = '';
        if(dt.id==id)
            selected = 'selected';
        var row = `<option value='${dt.id}' ${selected}>${dt.nama}</option>`;
        select.append(row);
    });
}

function ribuanId(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function tokenCek(akses_grup_nama='general'){
    ajaxRequest(base_url + '/api/token-cek/' + akses_grup_nama, 'GET', null, false,
        function(response) {
            console.log(response);
        },
        function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    );
}

function cariGrupAkses(nama) {
    var result = {grup_id: null, id: null};
    $.each(daftar_akses, function(index, dt) {
        if (dt.nama.toLowerCase() == nama.toLowerCase()) {
            result =  {
                grup_id: dt.grup_id,
                id: dt.id
            };
            return false;
        }
    });
    return result;
}

function timeAgo(datetime) {
    const now = new Date();
    const time = new Date(datetime);
    const secondsPast = (now.getTime() - time.getTime()) / 1000;
    
    if (secondsPast < 60) {
        return `${Math.floor(secondsPast)} detik lalu`;
    }
    if (secondsPast < 3600) {
        return `${Math.floor(secondsPast / 60)} menit lalu`;
    }
    if (secondsPast < 86400) {
        return `${Math.floor(secondsPast / 3600)} jam lalu`;
    }
    if (secondsPast < 2592000) { // 30 hari
        return `${Math.floor(secondsPast / 86400)} hari lalu`;
    }
    if (secondsPast < 31536000) { // 12 bulan
        return `${Math.floor(secondsPast / 2592000)} bulan lalu`;
    }
    return `${Math.floor(secondsPast / 31536000)} tahun lalu`;
}

function terbilang(nilai) {
    // deklarasi variabel nilai sebagai angka matemarika
    // Objek Math bertujuan agar kita bisa melakukan tugas matemarika dengan javascript
    nilai = Math.floor(Math.abs(nilai));

    // deklarasi nama angka dalam bahasa indonesia
    var huruf = [
      '',
      'Satu',
      'Dua',
      'Tiga',
      'Empat',
      'Lima',
      'Enam',
      'Tujuh',
      'Delapan',
      'Sembilan',
      'Sepuluh',
      'Sebelas',
      ];

    // menyimpan nilai default untuk pembagian
    var bagi = 0;
    // deklarasi variabel penyimpanan untuk menyimpan proses rumus terbilang
    var penyimpanan = '';

    // rumus terbilang
    if (nilai < 12) {
      penyimpanan = ' ' + huruf[nilai];
    } else if (nilai < 20) {
      penyimpanan = terbilang(Math.floor(nilai - 10)) + ' Belas';
    } else if (nilai < 100) {
      bagi = Math.floor(nilai / 10);
      penyimpanan = terbilang(bagi) + ' Puluh' + terbilang(nilai % 10);
    } else if (nilai < 200) {
      penyimpanan = ' Seratus' + terbilang(nilai - 100);
    } else if (nilai < 1000) {
      bagi = Math.floor(nilai / 100);
      penyimpanan = terbilang(bagi) + ' Ratus' + terbilang(nilai % 100);
    } else if (nilai < 2000) {
      penyimpanan = ' Seribu' + terbilang(nilai - 1000);
    } else if (nilai < 1000000) {
      bagi = Math.floor(nilai / 1000);
      penyimpanan = terbilang(bagi) + ' Ribu' + terbilang(nilai % 1000);
    } else if (nilai < 1000000000) {
      bagi = Math.floor(nilai / 1000000);
      penyimpanan = terbilang(bagi) + ' Juta' + terbilang(nilai % 1000000);
    } else if (nilai < 1000000000000) {
      bagi = Math.floor(nilai / 1000000000);
      penyimpanan = terbilang(bagi) + ' Miliar' + terbilang(nilai % 1000000000);
    } else if (nilai < 1000000000000000) {
      bagi = Math.floor(nilai / 1000000000000);
      penyimpanan = terbilang(nilai / 1000000000000) + ' Triliun' + terbilang(nilai % 1000000000000);
    }

    // mengambalikan nilai yang ada dalam variabel penyimpanan
    return penyimpanan;
}

function logoutWeb() {
    forceLogout();
    toastr.success('logout berhasil, akan diarahkan ke halaman login!', 'logout berhasil', {
        timeOut: 1000
    });
}

function forceLogout(pesan) {
    $.ajax({
        url: base_url + '/api/logout',
        type: 'post',
        success: function(response) {
            console.log(respose);
        },
    });

    localStorage.removeItem('access_token');
    localStorage.removeItem('akses_grup_id');
    localStorage.removeItem('akses_id');
    localStorage.removeItem('daftar_akses');
    localStorage.removeItem('akses_grup');
    localStorage.removeItem('user_id');
    localStorage.removeItem('email');
    localStorage.removeItem('name');
    window.location.replace(base_url + '/login');
    if (pesan){
        toastr.error(pesan, 'terjadi kesalahan');            
        // alert(pesan);
    }
}