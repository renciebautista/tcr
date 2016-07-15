// jQuery(document).ready(function($) {		                    
// 	    $('#customers').change(function(){			 

// 	    	$.getJSON("{{ url('auditreport/filter')}}",
// 	    	{ option: $('#customers').val() }, 
// 	    	function(json){
// 			    if ( json.length == 0 ) {
// 			        $.getJSON("{{ url('auditreport/alluserfilter')}}",				          
// 			        function(data) {		        			         	
// 	                	$('#users').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#users').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#users").trigger("change");                						
// 						$("#users").multiselect('destroy');
// 						$("#users").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });
// 				    $.getJSON("{{ url('auditreport/allstorefilter')}}",				         
// 			        function(data) {		        			         	
// 	                	$('#stores').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#stores").trigger("change");                						
// 						$("#stores").multiselect('destroy');
// 						$("#stores").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });
// 				    $.getJSON("{{ url('auditreport/alltemplatesfilter')}}",				         
// 			        function(data) {		        			         	
// 	                	$('#templates').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#templates').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#templates").trigger("change");                						
// 						$("#templates").multiselect('destroy');
// 						$("#templates").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });							
// 			    }
// 			    else{
// 				    $.getJSON("{{ url('auditreport/filter')}}",
// 			         { option: $('#customers').val() }, 
// 			        function(data) {		        			         	
// 	                	$('#users').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#users').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#users").trigger("change");                						
// 						$("#users").multiselect('destroy');
// 						$("#users").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });				    	
// 			        $.getJSON("{{ url('auditreport/storefilter')}}",
// 			         { option: $('#customers').val() }, 
// 			        function(data) {		        			         	
// 	                	$('#stores').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#stores").trigger("change");                						
// 						$("#stores").multiselect('destroy');
// 						$("#stores").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });

// 				    $.getJSON("{{ url('auditreport/templatesfilter')}}",
// 			         { option: $('#customers').val() }, 
// 			        function(data) {		        			         	
// 	                	$('#templates').empty();
// 	                	$.each(data, function(index, value) {
// 	                    $('#templates').append('<option value="' + index +'">' + value + '</option>');
// 			        });
// 	                	$("#templates").trigger("change");                						
// 						$("#templates").multiselect('destroy');
// 						$("#templates").multiselect({
// 						 	maxHeight: 200,
// 						    includeSelectAllOption: true,
// 						    enableCaseInsensitiveFiltering: true,
// 						    enableFiltering: true,
// 						    buttonWidth: '100%',
// 							buttonClass: 'form-control',
// 						 });					
// 				    });							   
// 			    }
// 			});					        
// 		});			
// 	});