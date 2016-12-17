<!DOCTYPE html>
<html>
<head>
@include('main.head')
</head>
<body>
	<!-- NAV BAR -->
	@include('main.header')
	<!-- END NAV BAR -->
<div id="wrap">
	<!-- Content -->
	@yield('content')
	<!-- END Content -->
	<div id="push"></div>
</div>
	<!-- Footer -->
	@include('main.footer')
	<!-- END Footer -->
</body>
<!-- JavaScripts -->
@include('main.headjs')

@yield('js')
</html>
