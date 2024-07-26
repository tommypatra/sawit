@extends('akun/template')

@section('head')
<meta name="title" content="Tiket Timbang"/>
<link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

@endsection

@section('container')
<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
        <h3 class="text-white fw-bolder fs-2qx me-5">Tiket Timbang</h3>
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
                                    <span class="card-label fw-bolder text-dark">Tiket Timbang</span>
                                    <span class="text-muted mt-1 fw-bold fs-7">mengelola data stok masuk dari tiket timbang</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary tambah-baru"><i class="bi bi-plus-lg"></i></button>
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary buatkan-nota"><i class="bi bi-wallet2"></i></button>
                                    <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary refresh-data"><i class="bi bi-arrow-clockwise"></i></button>

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-icon btn-color-primary btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <div class="dropdown-menu p-4 w-300px">
                                            <ul>
                                                <li><a href="javascipt:;" target="_blank">Laporan Harian</a></li>
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

                                    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk-tab-pane" type="button" role="tab" aria-controls="masuk-tab-pane" aria-selected="true">Timbang Masuk</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="nota-tab" data-bs-toggle="tab" data-bs-target="#nota-tab-pane" type="button" role="tab" aria-controls="nota-tab-pane" aria-selected="false">Nota Pembayaran</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="masuk-tab-pane" role="tabpanel" aria-labelledby="masuk-tab" tabindex="0">
                                            <table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
                                                        <th class="min-w-20px"><input class="form-check-input cek-semua" type="checkbox" id="cek-semua"></th>
                                                        <th class="min-w-40px">No</th>
                                                        <th class="min-w-125px">Pelanggan</th>
                                                        <th class="min-w-125px">Timbang Bersih</th>
                                                        <th class="min-w-90px">File Pendukung</th>
                                                        <th class="min-w-90px">Waktu</th>
                                                        <th class="min-w-125px">Operator</th>
                                                        <th class="min-w-20px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-list">
                                                    <!-- Data akan dimuat di sini -->
                                                </tbody>
                                                
                                            </table>
                                            <a href="javascript:;" class="btn btn-success buatkan-nota"><i class="bi bi-wallet2"></i> Buatkan Nota</a>
                                            <!-- Pagination -->
                                            <nav aria-label="Page navigation" class="mb-5">
                                                <ul class="pagination justify-content-center" id="pagination">
                                                </ul>
                                            </nav>
                                            <!-- /Pagination -->
                                        </div>
                                        {{-- /tab-masuk --}}
                                        <div class="tab-pane fade" id="nota-tab-pane" role="tabpanel" aria-labelledby="nota-tab" tabindex="0">
                                            <table class="table align-middle table-row-bordered table-row-dashed gy-5" id="kt_table_widget_1">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-boldest fs-7 text-uppercase">
                                                        <th class="min-w-40px">No</th>
                                                        <th class="min-w-300px">Penerima/ Waktu/ Rincian</th>
                                                        <th class="min-w-90px">Total Berat</th>
                                                        <th class="min-w-90px">Pembayaran (Total/ Jenis/ Tempat)</th>
                                                        <th class="min-w-90px">Biaya Transfer</th>
                                                        <th class="min-w-100px">Operator</th>
                                                        <th class="min-w-20px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data-list-nota">
                                                    <!-- Data akan dimuat di sini -->
                                                </tbody>
                                                
                                            </table>
                                            <!-- Pagination -->
                                            <nav aria-label="Page navigation" class="mb-5">
                                                <ul class="pagination justify-content-center" id="pagination-nota">
                                                </ul>
                                            </nav>
                                            <!-- /Pagination -->
                                        </div>
                                        {{-- /tab-selesai --}}
                                    </div>
                                    {{-- /MyTabContent --}}                                    


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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form-modal" >
                <input type="hidden" name="id" id="id" >

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Form Tiket Timbang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-8 mb-2">
                        <label for="pelanggan_id" class="form-label">Pelanggan</label>
                        <select class="form-select" name="pelanggan_id" id="pelanggan_id" required></select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="text" class="form-control datepicker" name="tanggal" value="{{ date('Y-m-d') }}" id="tanggal" required>
                    </div>
                    <div class="col-4 mb-2">
                        <label for="timbang_bersih" class="form-label">Timbang Bersih (Kg)</label>
                        <input type="text" class="form-control" name="timbang_bersih"  id="timbang_bersih" required>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file_upload" name="file_upload" accept="image/*">
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

<div class="modal fade" id="modal-form-nota" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form-modal-nota" >
                <input type="hidden" name="id" id="id" >
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nota Pembayaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="waktu" class="form-label">Tanggal</label>
                            <input type="text" class="form-control datepickertime" name="waktu" value="{{ date('Y-m-d H:i:s') }}" id="waktu" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="nama" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" name="nama"  id="nama" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="jenis_bayar" class="form-label">Jenis Bayar</label>
                            <select class="form-select" name="jenis_bayar"  id="jenis_bayar" required>
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="sumber_bayar_id" class="form-label">Sumber Bayar</label>
                            <select class="form-select" name="sumber_bayar_id" id="sumber_bayar_id" required></select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="biaya_transfer" class="form-label">Biaya Transfer</label>
                            <input type="number" class="form-control" name="biaya_transfer"  id="biaya_transfer" value="0" required>
                        </div>
                    </div>
                        <div class="row mb-2">
                            <div class="col-md-1">No</div>
                            <div class="col-md-6">Pelanggan/ Tanggal Masuk</div>
                            <div class="col-md-5">Transaksi (Jumlah/Harga/Total)</div>
                        </div>
                        <div id="notaList"></div>
                        <hr>
                        <h1 id="total-keseluruhan-angka">Rp. 0</h1>
                        <h4 id="total-keseluruhan-terbilang"></h4>

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
<script src="{{ url('js/akun/timbang_tiket.js') }}"></script>
<script src="{{ url('js/akun/timbang_tiket_selesai.js') }}"></script>
@endsection