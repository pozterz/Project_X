<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">

	    <title>Queue System</title>

	    <!-- Fonts -->
	    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
	    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700" type="text/css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue-pink.min.css">
		<link href="{{ url('css/materialstyle.css') }}"  rel='stylesheet' type='text/css'>

	    <!-- Styles -->
</head>
<body>
	<div class="mdl-layout__container">
	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
		<!-- NAV BAR -->
		@include('material.header')
		<!-- END NAV BAR -->
		<main class="mdl-layout__content">
			<!-- Content -->
			@yield('content')
			<!-- END Content -->
		
			<!-- Footer -->
			@include('material.footer')
			<!-- END Footer -->
		</main>
	</div>
	</div>
</body>
<!-- JavaScripts -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</html>