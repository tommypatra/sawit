<!DOCTYPE html>
<html lang="en">
<head>
	@include('partials/head')
	@yield('head')
</head>

<!--begin::Body-->
<body >

	<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed auth-page-bg">     
		<!--begin::Content-->
		<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
			<!--begin::Logo-->
			{{-- <a href="/ceres-html-free/index.html" class="mb-12">
				<img alt="Logo" src="{{ url('templates/admin/ceres') }}/assets/media/logos/default.svg" class="h-30px theme-light-show"/>
			</a>      --}}
			<!--end::Logo-->

			<!--begin::Wrapper-->
			<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
				
				<!--begin::Form-->
				<form class="form w-100" id="form-auth">
					<!--begin::Heading-->
					<div class="text-center mb-10">
						<!--begin::Title-->
						<h1 class="text-dark mb-3">Login Aplikasi Sawit</h1>
						<!--end::Title-->

						<!--begin::Link-->
						<div class="text-gray-400 fw-semibold fs-4">
							belum punya akun? Hubungi penyedia
						</div>
						<!--end::Link-->
					</div>
					<!--begin::Heading-->

					<div class="fv-row mb-10">
						<label class="form-label fs-6 fw-bold text-dark" for="email">Email</label>
						<input class="form-control form-control-lg form-control-solid" type="email" id="email" name="email" value="admin@app.com" required/>
					</div>
					<div class="fv-row mb-10">
						<div class="d-flex flex-stack mb-2">
							<label class="form-label fs-6 fw-bold text-dark" for="password">Password</label>
							{{-- <a href="/ceres-html-free/authentication/sign-in/password-reset.html" class="link-primary fs-6 fw-bold">
								Forgot Password ?
							</a> --}}
						</div>
						<input class="form-control form-control-lg form-control-solid" id="password" type="password" name="password" value="00000000" required minlength="8"/>
					</div>
					<div class="text-center">
						<button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
							<span class="indicator-label">
								Masuk
							</span>            
						</button>

						{{-- <div class="text-center text-muted text-uppercase fw-bold mb-5">or</div> --}}

						<!--begin::Google link-->
						{{-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
							<img alt="Logo" src="{{ url('templates/admin/ceres') }}/assets/media/svg/brand-logos/google-icon.svg" class="h-20px me-3"/>   
							Masuk Dengan Akun Google
						</a> --}}
						<!--end::Google link-->
					</div>
					<!--end::Actions-->
				</form>
				<!--end::Form--> 
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Content-->
		@include('partials/footer')
		
	</div>

	<div class="modal fade" id="loading-modal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body text-center">
					<img src="{{ asset('images/loading.gif') }}" height="150px" alt="Loading..." />
					<p>Sabar... lagi proses <span class='proses-berjalan'></span></p>
				</div>
			</div>
		</div>
	</div>
	
	<script>
		var hostUrl = "{{ url('templates/admin/ceres') }}/assets/";
		var base_url = "{{ url('/') }}";
	</script>
	<script src="{{ url('templates/admin/ceres') }}/assets/plugins/global/plugins.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/js/scripts.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/js/custom/widgets.js"></script>
	{{-- validate --}}
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
	<script src="{{ url('plugins/toastr/build/toastr.min.js') }}"></script>
	<script src="{{ url('js/general.js') }}"></script>
    <script src="{{ url('js/token.js') }}"></script>
	<script src="{{ url('js/crud.js') }}"></script>
	<script src="{{ url('js/auth.js') }}"></script>
</body>
</html>