<!DOCTYPE html>
<html>
<head>
@include('main.head')
<style type="text/css" media="screen">
	.white-space-pre-line {
    white-space: pre-line;
	}
	.modal { max-height: 85% !important }
</style>
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
