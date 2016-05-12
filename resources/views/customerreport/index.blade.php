@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('customerreport.create'), 'method' => 'POST')) !!}
        <div class="box-header with-border">
          	<h3 class="box-title">Customer Summary Report Filters</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Customer</label>
	                {!! Form::select('customers[]', $customers, null, array('class' => 'form-control select_form', 'id' => 'customers', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>

	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Region</label>
	                {!! Form::select('regions[]', $regions, null, array('class' => 'form-control select_form', 'id' => 'regions', 'multiple' => 'multiple')) !!}
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
	                <label>Audit Month</label>
	                {!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            
          	</div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Process</button>
            <button type="submit" class="btn btn-success">Download</button>
        </div>
        {{  Form::close() }}
    </div>



	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Customer</th>
								<th>Region</th>
								<th>Audit Template</th>
								<th>Audit Month</th>
								<th class="center">Stores Mapped</th>
								<th class="center">Stores Visited</th>
								<th class="center">Perfect Stores</th>
								<th class="center">OSA</th>
								<th class="center">NPI</th>
								<th class="center">Planogram</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($customer_summaries as $summary)
							<tr>
								<td>{{ $summary->customer }}</td>
								<td>{{ $summary->region }}</td>
								<td>{{ $summary->audit_tempalte }}</td>
								<td>{{ $summary->audit_group }}</td>
								<td class="center">{{ $summary->mapped_stores }}</td>
								<td class="center">{{ $summary->visited_stores }}</td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
								<td class="center"></td>
								<td>
								</td>
							</tr>
							@endforeach
							
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
</section>

@endsection

@section('page-script')
$('#customers,#audits, #templates, #regions').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
@endsection