@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('customizedplanoreport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">Customized Planogram Report</h3>
        </div>
        <div class="box-body">
          	<div class="row">
          		<div class="col-md-3">
	              	<div class="form-group">
	                	<label>Customers</label>
	               		{!! Form::select('customers[]', $customers, null, array('class' => 'form-control select_form', 'id' => 'customers', 'multiple' => 'multiple')) !!}
	              	</div>
	            </div>
	            <div class="col-md-3">
	            	<div class="form-group">
	                	<label>Audit Template</label>
	               		{!! Form::select('templates[]', $templates, null, array('class' => 'form-control select_form', 'id' => 'templates', 'multiple' => 'multiple')) !!}
	              	</div>
	            </div>	           	            	           
	            <div class="col-md-3">
		            <div class="form-group">
		                <label>Categories</label>
		               	{!! Form::select('categories[]', $categories, null, array('class' => 'form-control select_form', 'id' => 'categories', 'multiple' => 'multiple')) !!}
	              	</div>
	            </div>
             	<div class="col-md-3">
	              	<div class="form-group">
	                	<label>Audit Month</label>
	                	{!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              	</div>
	            </div>
          	</div>
        </div>

        <div class="box-footer">
            <button type="submit" name="submit" value="process" class="btn btn-primary">Process</button>
            <button type="submit" name="submit" value="download" class="btn btn-success">Download</button>
        </div>
        {{  Form::close() }}

     </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box box-padding">
				<div class="box-header">
					<h5 class="pull-right">{{ count($skus) }} {{str_plural('record', count($skus))}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Audit Month</th>
								<th>Customers</th>
								<th>Audit Template</th>
								<th>Category</th>
								<th>Planogram</th>
								<th class="right" >Store Count</th>
								<th class="right" >Store Compliance</th>
								<th class="right">Compliance Rate %</th>
								<th class="center">Details</th>
							</tr>
						</thead>
						<tbody>
							
							@if(count($skus) > 0)
							<?php $cnt = 1; ?>
							@foreach($skus as $sku)
							<tr>
								<td>{{ $cnt }}</td>
								<td>{{ $sku->description }}</td>
								<td>{{ $sku->customer }}</td>
								<td>{{ $sku->template }}</td>
								<td>{{ $sku->category }}</td>
								<td>{{ $sku->prompt }}</td>
								<td align="center">{{ $sku->store_count }}</td>
								<td align="center">{{ $sku->availability }}</td>
								<td align="center">{{ number_format($sku->osa_percent,2) }}%</td>
								<td>{!! link_to_action('CustomizedPlanogramReportController@getstoresinPLANO', 'View Stores', ['customer' => $sku->customer, 'template' => $sku->template,'category' => $sku->category,'prompt' => $sku->prompt,'description'=>$sku->description], ['class' => 'btn btn-xs btn btn-primary']) !!}</td>
							</tr>
							<?php $cnt++; ?>
							@endforeach
							@else
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							@endif
							
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
</section>

@endsection

@section('page-script')
$('#audits,#categories').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });

$('#customers').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',
}).on("change", function(){
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected'))},
		url: "../auditreport/templatesfilter",
		success: function(data){			
			$('select#templates').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#templates'));
			});
		$('select#templates').multiselect('rebuild');
		}
	});
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected'))},
		url: "../customizedplanoreport/categoryfilter",
		success: function(data){			
			$('select#categories').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#categories'));
			});
		$('select#categories').multiselect('rebuild');
		}
	});
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected'))},
		url: "../auditreport/monthfilter",
		success: function(data){			
			$('select#audits').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#audits'));
			});
		$('select#audits').multiselect('rebuild');
		}
	});
});

$('#templates').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',
}).on("change", function(){	
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected')), templates: GetSelectValues($('select#templates :selected'))},
		url: "../customizedplanoreport/categoryfilter",
		success: function(data){			
			$('select#categories').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#categories'));
			});
		$('select#categories').multiselect('rebuild');
		}
	});
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected')), templates: GetSelectValues($('select#templates :selected'))},
		url: "../customizedplanoreport/monthfilter",
		success: function(data){			
			$('select#audits').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#audits'));
			});
		$('select#audits').multiselect('rebuild');
		}
	});
});

$('#categories').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 }).on("change", function(){		
	$.ajax({
		type:"POST",
		data: {customers: GetSelectValues($('select#customers :selected')), templates: GetSelectValues($('select#templates :selected')),categories: GetSelectValues($('select#categories :selected'))},
		url: "../customizedplanoreport/monthfilter",
		success: function(data){			
			$('select#audits').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#audits'));
			});
		$('select#audits').multiselect('rebuild');
		}
	});
});

 $('#dt-table').dataTable();
@endsection