@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('npireport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">Per SKU NPI Report</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Name</label>
	                {!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Template</label>
	               	{!! Form::select('templates[]', $templates, null, array('class' => 'form-control select_form', 'id' => 'templates', 'multiple' => 'multiple')) !!}
	              </div>
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
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Audit Name</th>
								<th>Audit Template</th>
								<th>Category</th>
								<th>SKU</th>
								<th class="right" >Store Count</th>
								<th class="right" >Availability</th>
								<th class="right">OSA %</th>
								<th class="center">Action</th>
							</tr>
						</thead>
						<tbody>
							
							@if(count($skus) > 0)
							<?php $cnt = 1; ?>
							@foreach($skus as $sku)
							<tr>
								<td>{{ $cnt }}</td>
								<td>{{ $sku->description }}</td>
								<td>{{ $sku->template }}</td>
								<td>{{ $sku->category }}</td>
								<td>{{ $sku->prompt }}</td>
								<td class="right" >{{ $sku->store_count }}</td>
								<td class="right" >{{ $sku->availability }}</td>
								<td class="right" >{{ number_format($sku->osa_percent,2) }}%</td>
								<td></td>
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
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@section('page-script')
$('#audits,#templates').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

});

$('#dt-table').dataTable();



@endsection