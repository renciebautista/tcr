<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>Trade Check Report</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.5 -->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/font-awesome-4.4.0/css/font-awesome.min.css") }}">
		<!-- Ionicons -->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/ionicons-2.0.1/css/ionicons.min.css") }}">
		<!-- date picker -->
    	<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css")}}">
		<!-- DataTables -->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css")}}">
		<!-- Fancy Tree -->
		<link rel="stylesheet" href="{{ asset("/plugins/fancytree-2.10.2/skin-xp/ui.fancytree.min.css")}}">
		<!-- Bootstrap Multiselect -->
		<link rel="stylesheet" href="{{ asset("/plugins/bootstrap-multiselect-0.9.13/css/bootstrap-multiselect.css")}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}">
		<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
		page. However, you can choose any other skin. Make sure you
		apply the skin class to the body tag so the changes take effect.
		-->
		<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/dist/css/skins/skin-blue.min.css")}}">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<link rel="stylesheet" href="{{ asset("/plugins/lou-multi-select-759348a/css/multi-select.css")}}">

		<link rel="stylesheet" href="{{ asset("/plugins/At.js-1.3.2/css/jquery.atwho.min.css")}}">

		<link rel="stylesheet" href="{{ asset("/css/style.css")}}">
	</head>
	<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<!-- Header -->
			@include('layouts.header')
			<!-- Full Width Column -->

			<div class="content-wrapper" style="min-height: 192px;">
				<div class="container">
					<!-- <section class="content-header">
					  <h1>
						Fixed Layout
						<small>Blank example to the fixed layout</small>
					  </h1>
					  <ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
						<li><a href="#">Layout</a></li>
						<li class="active">Fixed</li>
					  </ol>
					</section> -->
					 @yield('content')
				</div>
			   
			</div><!-- /.content-wrapper -->

			@include('layouts.footer')

		</div><!-- ./wrapper -->

		<!-- jQuery 2.1.4 -->
		<script src="{{ asset ("/bower_components/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
		<!-- jQueryUI -->
		<script src="{{ asset ("/bower_components/AdminLTE/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
		<!-- Bootstrap 3.3.5 -->
		<script src="{{ asset ("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
		<!-- InputMask -->
	    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js") }}"></script>
	    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js") }}"></script>
	    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js") }}"></script>
	    <!-- date-picker -->
	    <script src="{{ asset ("/bower_components/AdminLTE/plugins/moment/moment.min.js") }}"></script>
	    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}"></script>

		<script src="{{ asset ("/plugins/lou-multi-select-759348a/js/jquery.multi-select.js") }}"></script>

		<script src="{{ asset ("/plugins/Caret.js-0.2.2/jquery.caret.min.js") }}"></script>
		<script src="{{ asset ("/plugins/At.js-1.3.2/js/jquery.atwho.min.js") }}"></script>

		<!-- DataTables -->
    	<script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js") }}"></script>
    	<script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js") }}"></script>

    	<!-- Fancy Tree -->
    	<script src="{{ asset ("/plugins/fancytree-2.10.2/jquery.fancytree.min.js") }}"></script>

    	<!-- Bootstrap Multiselect -->
    	<script src="{{ asset ("/plugins/bootstrap-multiselect-0.9.13/js/bootstrap-multiselect.js") }}"></script>

    	<!-- Numeric Input -->
    	<script src="{{ asset ("/plugins/jQuery.numeric_input/jquery.numeric_input.min.js") }}"></script>
    	
		<script type="text/javascript">
			$(document).ready(function() {
				@section('page-script')

				@show
			});
		</script>
	</body>
</html>
