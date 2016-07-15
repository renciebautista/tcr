// jQuery(document).ready(function($) {				             	       
// 		    $('#users').change(function(){			 
// 		    	$.getJSON("{{ url('auditreport/userstorefilter')}}",
// 		    	{ option: $('#users').val() }, 
// 		    	function(json){
// 				    if ( json.length == 0 ) {				        				    	
// 					    $.getJSON("{{ url('auditreport/allstorefilter')}}",				         
// 				        function(data) {		        			         	
// 		                	$('#stores').empty();
// 		                	$.each(data, function(index, value) {
// 		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
// 				        });
// 		                	$("#stores").trigger("change");                						
// 							$("#stores").multiselect('destroy');
// 							$("#stores").multiselect({
// 							 	maxHeight: 200,
// 							    includeSelectAllOption: true,
// 							    enableCaseInsensitiveFiltering: true,
// 							    enableFiltering: true,
// 							    buttonWidth: '100%',
// 								buttonClass: 'form-control',
// 							 });					
// 					    });					    
// 				    }
// 				    else{    			   
// 				        $.getJSON("{{ url('auditreport/userstorefilter')}}",
// 				        { option: $('#users').val() },					        
// 					        function(data) {		        			         	
// 		                	$('#stores').empty();
// 		                	$.each(data, function(index, value) {
// 		                    $('#stores').append('<option value="' + index +'">' + value + '</option>');
// 				        	});
// 		                	$("#stores").trigger("change");                						
// 							$("#stores").multiselect('destroy');
// 							$("#stores").multiselect({
// 							 	maxHeight: 200,
// 							    includeSelectAllOption: true,
// 							    enableCaseInsensitiveFiltering: true,
// 							    enableFiltering: true,
// 							    buttonWidth: '100%',
// 								buttonClass: 'form-control',
// 							});					
// 				    	});					    
// 		 			}
// 				});
// 			});
// 		});