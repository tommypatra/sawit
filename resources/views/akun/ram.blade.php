@extends('akun/template')

@section('head')
<meta name="title" content="Timbang Berangkat"/>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<style>
    .buram {
        background-color: #d9d9d9; /* Warna abu-abu lebih gelap dari putih */
        color: #999; /* Warna teks agak redup */
        border: 1px solid #bbb; /* Border abu-abu */
        pointer-events: none; /* Tidak bisa diklik */
        user-select: none; /* Tidak bisa diseleksi */
        cursor: not-allowed; /* Kursor menunjukkan larangan */
    }

</style>
@endsection

@section('container')
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <h3 class="text-white fw-bolder fs-2qx me-5">RAM</h3>
        <div class="d-flex align-items-center flex-wrap py-2">
            <div id="kt_header_search" class="d-flex align-items-center w-200px w-lg-250px my-2 me-4 me-lg-6" data-kt-search-keypress="true" data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu" data-kt-menu-trigger="auto" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end">

                <div data-kt-search-element="form" class="search w-100 position-relative" autocomplete="off">
                    <span class="position-absolute top-50 translate-middle-y ms-5">
                        <i class="bi bi-search fs-2"></i>
                    </span>
                    <input type="text" class="form-control ps-15" id="search-input" value="" placeholder="Cari..." data-kt-search-element="input" />									
                </div>
                
            </div>
            {{-- <a href="javascript:;" class="btn btn-custom btn-color-white btn-active-color-success my-2 me-2 me-lg-6" data-bs-toggle="modal" data-bs-target="#kt_modal_invite_friends">Invite Friend</a> --}}
            <a href="javascript:;" class="btn btn-success tambah-baru" >Tambah Baru</a>
        </div>
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
                            <div class="card-header">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder text-dark">RAM</span>
                                    <span class="text-muted mt-1 fw-bold fs-7">mengelola data RAM</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary tambah-baru"><i class="bi bi-plus-lg"></i></button>
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary refresh-data"><i class="bi bi-arrow-clockwise"></i></button>

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <div class="dropdown-menu p-4 w-300px">
                                            <ul>
                                                <li><a href="{{ route('cetak-timbang-pabrik') }}" target="_blank">Timbang Pabrik</a></li>
                                            </ul>
                                        </div>
                                    </div>                                    

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                            <i class="bi bi-funnel"></i>
                                        </button>
                                        <form class="dropdown-menu p-4 w-300px">
                                            <div class="mb-3">
                                                <label for="filter-input" class="form-label">Filter</label>
                                                <input type="text" class="form-control" id="filter-input" placeholder="">
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="dropdownCheck2">
                                                <label class="form-check-label" for="dropdownCheck2">
                                                    Cek
                                                </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </form>
                                    </div>
                                                                
                                </div>
                            </div>                            
                            <div class="card-body py-0 mb-3">

                                <div class="table-responsive">
                                    <table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
                                                <th class="min-w-40px">No</th>
                                                <th class="min-w-150px">RAM</th>
                                                <th class="min-w-200px">Alamat</th>
                                                <th class="min-w-75px">Aktif</th>
                                                <th class="min-w-20px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="data-list">
                                            <!-- Data akan dimuat di sini -->
                                        </tbody>
                                        
                                    </table>
                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation" class="mb-5">
                                        <ul class="pagination justify-content-center" id="pagination">
                                        </ul>
                                    </nav>
                                    <!-- /Pagination -->                                                                      


                                </div>
                                {{-- /table-responsive --}}
                            </div>
                            {{-- /card-body --}}
                        </div>
                        {{-- /card --}}                    
                    </div>
                    {{-- /col --}}
                </div>
                {{-- /row --}}

            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-modal" >
                <input type="hidden" name="id" id="id" >

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Form RAM</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-sm-6 mb-2">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" id="nama" required>
                    </div>

                    <div class="col-sm-12 mb-2">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea rows="3" class="form-control" name="alamat" id="alamat" required></textarea>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <label for="is_aktif" class="form-label">Status</label>
                        <select class="form-control" name="is_aktif" id="is_aktif" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('script')
{{-- validate --}}
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
{{-- bootstrap-material-datetimepicker --}}
<script src="{{ asset('plugins/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
{{-- select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

{{-- script costum --}}
<script src="{{ url('js/token.js') }}"></script>
<script src="{{ url('js/crud.js') }}"></script>
<script src="{{ url('js/pagination.js') }}"></script>
<script>
    var operator_id=1;    
    var vLimit=25;
</script>
<script src="{{ url('js/akun/ram.js') }}"></script>
@endsection