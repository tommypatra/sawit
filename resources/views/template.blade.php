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
								<span class="svg-icon svg-icon-2x">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="black" />
										<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="black" />
									</svg>
								</span>
							</div>
						</div>
						<div class="header-logo me-5 me-md-10 flex-grow-1 flex-lg-grow-0">
							<a href="{{ url('/') }}">
								<h3 class="text-white fw-bolder fs-2qx me-5">Sawit App</h3>
							</a>
						</div>
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
							<div class="d-flex align-items-stretch" id="kt_header_nav">
								<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
									<div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch" id="#kt_header_menu" data-kt-menu="true">
										<div class="menu-item">											
											<a href="{{ url('/') }}" class="menu-link py-3">
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
							<div class="d-flex align-items-stretch flex-shrink-0">
								<div class="topbar d-flex align-items-stretch flex-shrink-0">
									<div class="nav-user-login" style="display:none;">									
										@include('partials/user_notifikasi')
										@include('partials/user_login')
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				{{-- judul --}}
				@yield('konten')
				<div class="toolbar py-5 py-lg-15" id="kt_toolbar">
					<div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap">
						<h3 class="text-white fw-bolder fs-2qx me-5">Todays’ Agenda</h3>
						<div class="d-flex align-items-center flex-wrap py-2">
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
											<div class="card-header border-0 pt-5 pb-3">
												<h3 class="card-title fw-bolder text-gray-800 fs-2">Judul</h3>
											</div>
											<div class="card-body py-0">
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

				@include('partials/footer')
			</div>
		</div>
	</div>

	@include('partials/menu_cepat')
	@include('partials/scroll_top')

	<script>
		var hostUrl = "{{ url('templates/admin/ceres') }}/assets/";
	</script>
	<script src="{{ url('js/auth.js') }}"></script>

	<script src="{{ url('templates/admin/ceres') }}/assets/plugins/global/plugins.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/js/scripts.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
	<script src="{{ url('templates/admin/ceres') }}/assets/js/custom/widgets.js"></script>
</body>
</html>