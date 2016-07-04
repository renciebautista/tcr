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
            <button type="submit" name="submit" value="process" class="btn btn-primary">Process</button>
            <button type="submit" name="submit" value="download" class="btn btn-success">Download</button>
        </div>
        {{  Form::close() }}
    </div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h5 class="pull-right">{{ count($customer_summaries) }} {{str_plural('record', count($customer_summaries))}} found.</h5>					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Customer</th>
								<th>Region</th>
								<th>Audit Template</th>
								<th>Audit Month</th>
								<th class="right">Stores Mapped</th>
								<th class="right">Stores Visited</th>
								<th class="right">Perfect Stores</th>
								<th class="right">Perfect Stores %</th>
								<th class="right">Perfect Stores Ave</th>
								<!-- <th class="right">Ave Category PS</th>
								<th class="right">Ave Category Door</th>								
								<th class="right">Ave Planogram</th> -->
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
								<td class="right">{{ $summary->mapped_stores }}</td>
								<td class="right">{{ $summary->visited_stores }}</td>
								<td class="right">{{ $summary->perfect_stores }}</td>
								<td class="right">{{ (number_format((float)$summary->perfect_stores/$summary->visited_stores,2,'.',','))*100}}%</td>
								<td class="right">{{ $summary->ave_perfect_stores }}%</td>
								<!-- <td class="right"></td>
								<td class="right"></td> -->
								<!-- <td class="right">{{ number_format($summary->osa_ave,2) }}%</td>
								<td class="right">{{ number_format($summary->npi_ave,2) }}%</td>
								<td class="right">{{ number_format($summary->planogram_ave,2) }}%</td> -->
								<td>
									{!! link_to_action('CustomerReportController@show', 'View Stores', ['customer_code' => $summary->customer_code, 'region_code' => $summary->region_code,'channel_code' => $summary->channel_code, 'audit_id' => $summary->audit_id], ['class' => 'btn btn-xs btn btn-primary']) !!}
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
$('#customers,#audits, #templates, #regions, #pjps').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
@endsection