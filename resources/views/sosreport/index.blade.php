@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('sosreport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">SOS Report</h3>
        </div>
        <div class="box-body">
          	<div class="row">
          		<div class="col-md-3">
	              <div class="form-group">
	                <label>Customer Name</label>
	               	{!! Form::select('customers[]', $customers, null, array('class' => 'form-control select_form', 'id' => 'customers', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Name</label>
	                {!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>	            
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Template</label>
	               	{!! Form::select('templates[]', $templates, null, array('class' => 'form-control select_form', 'id' => 'templates_sos', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Category</label>
	                {!! Form::select('categories[]', $categories, null, array('class' => 'form-control select_form', 'id' => 'categories', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>	            
          	</div>
          	<div class="row">	            
	            <div class="col-md-3">
	             	<div class="form-group">
	                	<label>User</label>
	                	{!! Form::select('users[]', $users, null, array('class' => 'form-control select_form', 'id' => 'users', 'multiple' => 'multiple')) !!}
	              	</div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Store Name</label>
	               	{!! Form::select('stores[]', $stores, null, array('class' => 'form-control select_form', 'id' => 'stores', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              
	            </div>
	            <div class="col-md-3">
	              
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
					<h5 class="pull-right">{{ count($soss) }} {{str_plural('record', count($soss))}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Audit Name</th>
								<th>Customer Name</th>
								<th>Audit Template</th>
								<th>User</th>
								<th>Store Name</th>
								<th>Category</th>
								<th class="right" >Target</th>
								<th class="right" >PS SOS Measurement</th>
								<th class="center" >Achievement</th>
							</tr>
						</thead>
						<tbody>
							
							@if(count($soss) > 0)
							<?php $cnt = 1; ?>
							@foreach($soss  as $sos)
							<tr>
								<td>{{ $sos->description }}</td>
								<td>{{ $sos->customer }}</td>
								<td>{{ $sos->template }}</td>
								<td>{{ $sos->name }}</td>
								<td>{{ $sos->store_name }}</td>
								<td>{{ $sos->category }}</td>
								<td align="center">{{ number_format($sos->target,2) }}%</td>
								<td align="center">
									@if($sos->sos_measurement != '')
									{{ number_format($sos->sos_measurement,2) }}%
									@endif
								</td>
								
								<td class="center">
									@if($sos->sos_measurement >= $sos->target )
										 <i class="fa fa-fw fa-check"></i>
									@else
									@endif
								</td>
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
$('#audits,#stores, #categories, #customers, #templates_sos, #users').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });


$('#dt-table').dataTable();
@endsection