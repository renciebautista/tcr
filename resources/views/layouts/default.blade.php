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

		<link rel="stylesheet" href="{{ asset("/plugins/dataTables/dataTables.bootstrap.css")}}">

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

    	<script src="{{ asset ("/plugins/dataTables/jquery.dataTables.js") }}"></script>
    	<script src="{{ asset ("/plugins/dataTables/dataTables.bootstrap.js") }}"></script>
    	

    	
		<script type="text/javascript">
			$(document).ready(function() {
				@section('page-script')

				@show
			});
		</script>
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#customers').change(function(){			 

		    	$.getJSON("{{ url('auditreport/filter')}}",
		    	{ option: $('#customers').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {
				        $.getJSON("{{ url('auditreport/alluserfilter')}}",				          
				        function(data) {		        			         	
		                	$('#users').empty();
		                	$.each(data, function(index, value) {
		                    $('#users').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#users").trigger("change");                						
							$("#users").multiselect('destroy');
							$("#users").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });
					    $.getJSON("{{ url('auditreport/allstorefilter')}}",				         
				        function(data) {		        			         	
		                	$('#stores').empty();
		                	$.each(data, function(index, value) {
		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#stores").trigger("change");                						
							$("#stores").multiselect('destroy');
							$("#stores").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });
					    $.getJSON("{{ url('auditreport/alltemplatesfilter')}}",				         
				        function(data) {		        			         	
		                	$('#templates').empty();
		                	$.each(data, function(index, value) {
		                    $('#templates').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#templates").trigger("change");                						
							$("#templates").multiselect('destroy');
							$("#templates").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });				
					    $.getJSON("{{ url('customerregionalreport/allregionsfilter')}}",				         
				        function(data) {		        			         	
		                	$('#regions').empty();
		                	$.each(data, function(index, value) {
		                    $('#regions').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#regions").trigger("change");                						
							$("#regions").multiselect('destroy');
							$("#regions").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });				
					    $.getJSON("{{ url('osareport/allcategoryfilter')}}",				         
				        function(data) {		        			         	
		                	$('#categories').empty();
		                	$.each(data, function(index, value) {
		                    $('#categories').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });							
				    }
				    else{
					    $.getJSON("{{ url('auditreport/filter')}}",
				         { option: $('#customers').val() }, 
				        function(data) {		        			         	
		                	$('#users').empty();
		                	$.each(data, function(index, value) {
		                    $('#users').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#users").trigger("change");                						
							$("#users").multiselect('destroy');
							$("#users").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });				    	
				        $.getJSON("{{ url('auditreport/storefilter')}}",
				         { option: $('#customers').val() }, 
				        function(data) {		        			         	
		                	$('#stores').empty();
		                	$.each(data, function(index, value) {
		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#stores").trigger("change");                						
							$("#stores").multiselect('destroy');
							$("#stores").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });

					    $.getJSON("{{ url('auditreport/templatesfilter')}}",
				         { option: $('#customers').val() }, 
				        function(data) {		        			         	
		                	$('#templates').empty();
		                	$.each(data, function(index, value) {
		                    $('#templates').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#templates").trigger("change");                						
							$("#templates").multiselect('destroy');
							$("#templates").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });	
					    $.getJSON("{{ url('customerregionalreport/regionsfilter')}}",
				        { option: $('#customers').val() },					        
					        function(data) {		        			         	
		                	$('#regions').empty();
		                	$.each(data, function(index, value) {
		                    $('#regions').append('<option value="' + index +'">' + value + '</option>');
				        	});
		                	$("#regions").trigger("change");                						
							$("#regions").multiselect('destroy');
							$("#regions").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							});					
				    	});						    								  
				    }
				});					        
			});			
		});
		</script> 							
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#users').change(function(){			 
		    	$.getJSON("{{ url('auditreport/userstorefilter')}}",
		    	{ option: $('#users').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {				        				    	
					    $.getJSON("{{ url('auditreport/allstorefilter')}}",				         
				        function(data) {		        			         	
		                	$('#stores').empty();
		                	$.each(data, function(index, value) {
		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#stores").trigger("change");                						
							$("#stores").multiselect('destroy');
							$("#stores").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });					    
				    }
				    else{    			   
				        $.getJSON("{{ url('auditreport/userstorefilter')}}",
				        { option: $('#users').val() },					        
					        function(data) {		        			         	
		                	$('#stores').empty();
		                	$.each(data, function(index, value) {
		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
				        	});
		                	$("#stores").trigger("change");                						
							$("#stores").multiselect('destroy');
							$("#stores").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							});					
				    	});					    
		 			}
				});
			});
		});
		</script> 							
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#templates').change(function(){			 
		    	$.getJSON("{{ url('osareport/categoryfilter')}}",
		    	{ option: $('#templates').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {				        				    	
					    $.getJSON("{{ url('osareport/allcategoryfilter')}}",					         
				        function(data) {		        			         	
		                	$('#categories').empty();
		                	$.each(data, function(index, value) {
		                    $('#categories').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });					    
				    }
				    else{    			   
				        $.getJSON("{{ url('osareport/categoryfilter')}}",	
				        { option: $('#templates').val() }, 			         
						function(data) {		        			         	
							$('#categories').empty();
							$.each(data, function(index, value) {
						    $('#categories').append('<option value="' + index +'">' + value + '</option>');
						});
							$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
						});			
		 			}
				});
			});
		});
		</script> 							
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#templates_npi').change(function(){			 
		    	$.getJSON("{{ url('npireport/categoryfilter')}}",
		    	{ option: $('#templates_npi').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {				        				    	
					    $.getJSON("{{ url('npireport/allcategoryfilter')}}",					         
				        function(data) {		        			         	
		                	$('#categories').empty();
		                	$.each(data, function(index, value) {
		                    $('#categories').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });					    
				    }
				    else{    			   
				        $.getJSON("{{ url('npireport/categoryfilter')}}",	
				        { option: $('#templates_npi').val() }, 			         
						function(data) {		        			         	
							$('#categories').empty();
							$.each(data, function(index, value) {
						    $('#categories').append('<option value="' + index +'">' + value + '</option>');
						});
							$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
						});			
		 			}
				});
			});
		});
		</script> 							
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#templates_sos').change(function(){			 
		    	$.getJSON("{{ url('sosreport/categoryfilter')}}",
		    	{ option: $('#templates_sos').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {				        				    	
					    $.getJSON("{{ url('sosreport/allcategoryfilter')}}",					         
				        function(data) {		        			         	
		                	$('#categories').empty();
		                	$.each(data, function(index, value) {
		                    $('#categories').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });					    
				    }
				    else{    			   
				        $.getJSON("{{ url('sosreport/categoryfilter')}}",	
				        { option: $('#templates_sos').val() }, 			         
						function(data) {		        			         	
							$('#categories').empty();
							$.each(data, function(index, value) {
						    $('#categories').append('<option value="' + index +'">' + value + '</option>');
						});
							$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
						});			
		 			}
				});
			});
		});
		</script> 							
		<script type="text/javascript">
		jQuery(document).ready(function($) {				             	       
		    $('#templates_pla').change(function(){			 
		    	$.getJSON("{{ url('customizedplanoreport/categoryfilter')}}",
		    	{ option: $('#templates_pla').val() }, 
		    	function(json){
				    if ( json.length == 0 ) {				        				    	
					    $.getJSON("{{ url('customizedplanoreport/allcategoryfilter')}}",					         
				        function(data) {		        			         	
		                	$('#categories').empty();
		                	$.each(data, function(index, value) {
		                    $('#categories').append('<option value="' + index +'">' + value + '</option>');
				        });
		                	$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
					    });					    
				    }
				    else{    			   
				        $.getJSON("{{ url('customizedplanoreport/categoryfilter')}}",	
				        { option: $('#templates_pla').val() }, 			         
						function(data) {		        			         	
							$('#categories').empty();
							$.each(data, function(index, value) {
						    $('#categories').append('<option value="' + index +'">' + value + '</option>');
						});
							$("#categories").trigger("change");                						
							$("#categories").multiselect('destroy');
							$("#categories").multiselect({
							 	maxHeight: 200,
							    includeSelectAllOption: true,
							    enableCaseInsensitiveFiltering: true,
							    enableFiltering: true,
							    buttonWidth: '100%',
								buttonClass: 'form-control',
							 });					
						});			
		 			}
				});
			});
		});
		</script> 							
	</body>
</html>
