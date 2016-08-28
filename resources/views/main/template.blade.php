<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title>Queue System</title>
	    <meta name="keywords" content="Project" />
		<meta name="author" content="Tharathep Numuan | 5635512083" />

		<link rel="stylesheet"  type="text/css" href="{{ asset('css/reset.css') }}">
	    <!-- Fonts -->
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
 		<link rel="icon" href="{{ asset('icon.png') }}">
	    <!-- Styles -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="{{ url('/')}}{{ elixir('css/all.css') }}">

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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.3/js/materialize.min.js"></script>
    <script src="{{ asset('js/jquery.countdown.min.js')}}"></script>
    <script src="{{ url('/')}}{{ elixir('js/all.js')}}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    @yield('js')
</html>
