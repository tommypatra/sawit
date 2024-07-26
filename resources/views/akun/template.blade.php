<!DOCTYPE html>
<html lang="en">
<head>
	@include('partials/head')
	@yield('head')
</head>

<body id="kt_body" style="background-image: url({{ url('templates/admin/ceres') }}/assets/media/patterns/header-bg.png)" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled">
	<div class="d-flex flex-column flex-root">
		<div class="page d-flex flex-row flex-column-fluid">
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<div id="kt_header" class="header align-items-stretch" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
					<div class="container-xxl d-flex align-items-center">
						<div class="d-flex align-items-center d-lg-none ms-n2 me-3" title="Show aside menu">
							<div class="btn btn-icon btn-custom w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
								<i class="bi bi-list fs-1"></i>								
							</div>
						</div>
						<div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
							<a href="{{ url('/') }}">
								<h3 class="text-white fw-bolder fs-2qx me-5">SAWIT-APP</h3>
							</a>
						</div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
							<div class="d-flex align-items-stretch" id="kt_header_nav">
								<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
									<div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
										<div class="menu-item">											
											<a href="{{ route('dashboard') }}" class="menu-link py-3">
												<span class="menu-title">Dashboard</span>
											</a>
										</div>
										@include('menu/operator')							
										@include('menu/supir')							
										@include('menu/pelanggan')							
										@include('menu/pabrik')							
										@include('menu/admin')							
									</div>
								</div>
							</div>
							<div class="nav-user-login" style="display:none;">	
								<div class="d-flex align-items-stretch flex-shrink-0">
									<div class="topbar d-flex align-items-stretch flex-shrink-0">																	
										@include('partials/user_notifikasi')
										@include('partials/user_login')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				@yield('container')

				@include('partials/footer')
			</div>
		</div>
	</div>

	@include('partials/menu_cepat')
	@include('partials/scroll_top')

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
	<script src="{{ asset('plugins/toastr/build/toastr.min.js') }}"></script>
	<script src="{{ url('js/general.js') }}"></script>
	<script>
		toastr.options.closeButton = true;
	</script>
	@yield('script')
</body>
</html>