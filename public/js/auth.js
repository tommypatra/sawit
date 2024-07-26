$(document).ready(function() {
    cekTokenLogin();

    function cekTokenLogin() {
    var akses_grup_id = localStorage.getItem('akses_grup_id');
        if(akses_grup_id)
            ajaxRequest(base_url + '/api/token-cek/' + akses_grup_id, 'GET', null,false,
                function(response) {
                    redirectDashboard();
                },
                function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                }
            );    
    }

    function redirectDashboard(pesan='',timeout=1000){
        toastr.success('akan diarahkan ke halaman dashboard!', 'login', {
            vtimeout: timeout
        });
        var goUrl = base_url+'/dashboard';

        setTimeout(function() {window.location.replace(goUrl);}, timeout+500);
    }

    $("#form-auth").validate({
        submitHandler: function(form,e) {
            e.preventDefault();
            var formData = new FormData(form);
            ajaxRequest(base_url + '/api/auth-cek', 'post', $(form).serialize(), true,                 
                function(response) {
                    // console.log(response);
                    localStorage.setItem('access_token', response.access_token);
                    localStorage.setItem('akses_grup_id', response.akses_grup_id);
                    localStorage.setItem('akses_id', JSON.stringify(response.akses_id));
                    localStorage.setItem('daftar_akses', JSON.stringify(response.daftar_akses));
                    localStorage.setItem('user_id', response.user_id);
                    localStorage.setItem('email', response.email);
                    localStorage.setItem('name', response.name);
                    redirectDashboard();
                }
            );
        }
    });
});
