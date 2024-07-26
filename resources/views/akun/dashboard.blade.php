@extends('akun/template')

@section('head')
<meta name="title" content="Dashboard"/>
@endsection

@section('container')
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <h3 class="text-white fw-bolder fs-2qx me-5">Dashboard</h3>
        {{-- <div class="d-flex align-items-center flex-wrap py-2">
            <div id="kt_header_search" class="d-flex align-items-center w-200px w-lg-250px my-2 me-4 me-lg-6" data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu" data-kt-menu-trigger="auto" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end">

                <form data-kt-search-element="form" class="search w-100 position-relative" autocomplete="off">
                    <span class="position-absolute top-50 translate-middle-y ms-5">
                        <i class="bi bi-search fs-2"></i>
                    </span>
                    <input type="text" class="form-control ps-15" name="search" value="" placeholder="Search..." data-kt-search-element="input" />									
                </form>
                
            </div>
            <a href="#" class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">Invite Friend</a>
            <a href="#" class="btn btn-success my-2" tooltip="New App" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">New Goal</a>
        </div> --}}
    </div>
</div>

{{-- konten --}}
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card card-page">
            <div class="card-body">
                <div class="row gy-5 g-xl-8">

                    <div class="col-xxl-12">
                        <div class="card card-xxl-stretch">
                            <div class="card-header border-0 pt-5 pb-3">
                                <h3 class="card-title fw-bolder text-gray-800 fs-2">Dashboard</h3>
                            </div>
                            <div class="card-body py-0 mb-3">
                                <div class="table-responsive">
                                    Konten
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ url('js/token.js') }}"></script>
    <script>
        tokenCek();
        console.log('1 '+localStorage.getItem('access_token'));
        console.log('2 '+localStorage.getItem('akses_grup_id'));
        console.log('3 '+localStorage.getItem('akses_id'));
        console.log('4 '+localStorage.getItem('daftar_akses'));
        console.log('5 '+localStorage.getItem('user_id'));
        console.log('6 '+localStorage.getItem('email'));
        console.log('7 '+localStorage.getItem('name'));
    </script>
@endsection