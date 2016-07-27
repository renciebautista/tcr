@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('usersummaryreport.create'), 'method' => 'POST')) !!}
        <div class="box-header with-border">
          	<h3 class="box-title">User Summary Report</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>User</label>
	                {!! Form::select('users[]', $users, null, array('class' => 'form-control select_form', 'id' => 'users', 'multiple' => 'multiple')) !!}
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
		<div class="col-lg-3 col-xs-6">	
			@if($p_store_average >=65)
				<div class="small-box bg-green">
			@elseif($p_store_average >=50 && $p_store_average < 65)
				<div class="small-box bg-yellow">
			@elseif($p_store_average < 50 )					
				<div class="small-box bg-red">
			@endif					
				<div class="inner">
					<h3>{{$p_store_average}}<sup style="font-size: 20px"></sup></h3>
					<p>PS Category Average</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-graph-up-right"></i>
				</div>
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>	
		<div class="col-lg-3 col-xs-6">	
			@if($osa_average >=65)
				<div class="small-box bg-green">
			@elseif($osa_average >=50 && $osa_average < 65)
				<div class="small-box bg-yellow">
			@elseif($osa_average < 50 )					
				<div class="small-box bg-red">
			@endif					
				<div class="inner">
					<h3>{{$osa_average}}<sup style="font-size: 20px"></sup></h3>
					<p>OSA Average</p>
				</div>
				<div class="icon">
					<i class="ion ion-ios-pricetag"></i>					
				</div>
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>	
		<div class="col-lg-3 col-xs-6">	
			@if($npi_average >=65)
				<div class="small-box bg-green">
			@elseif($npi_average >=50 && $npi_average < 65)
				<div class="small-box bg-yellow">
			@elseif($npi_average < 50 )					
				<div class="small-box bg-red">
			@endif								
				<div class="inner">
					<h3>{{$npi_average}}<sup style="font-size: 20px"></sup></h3>
					<p>NPI Average</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>					
				</div>
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">	
			@if($planogram_average >=65)
				<div class="small-box bg-green">
			@elseif($planogram_average >=50 && $planogram_average < 65)
				<div class="small-box bg-yellow">
			@elseif($planogram_average < 50 )					
				<div class="small-box bg-red">
			@endif					
				<div class="inner">
					<h3>{{$planogram_average}}<sup style="font-size: 20px"></sup></h3>
					<p>Planogram Average</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="#" class="small-box-footer"></a>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h5 class="pull-right">{{ count($user_summaries) }} {{str_plural('record', count($user_summaries))}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-hover table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Audit Month</th>
								<th class="right">Stores Mapped</th>
								<th class="right">PJP Target</th>
								<th class="right">Stores Visited</th>
								<th class="right">PJP Compliance</th>
								<!-- <th class="right">To be Visited</th> -->
								<th class="right">Perfect Stores</th>
								<th class="right">%PS Doors</th>
								<th class="right">%PS Categories</th>
								<th class="right">OSA Ave</th>
								<th class="right">NPI Ave</th>
								<th class="right">Planogram Ave</th>								
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($user_summaries as $summary)
							<tr>
								<td>{{ $summary->name }}</td>
								<td>{{ $summary->description }}</td>
								<td align="center">{{ $summary->mapped_stores }}</td>
								<td align="center">{{ $summary->target }}</td>
								<td align="center">{{ $summary->store_visited }}</td>								
								<td align="center">{{ $summary->pjp_compliance}}%</td>																							
								<td align="center">{{ $summary->perfect_store }}</td>
								<td align="center">{{ $summary->ps_doors }}%</td>
								<td align="center">{{ $summary->ps_cat}}</td>	
								<td align="center">{{ $summary->osa }}</td>	
								<td align="center">{{ $summary->npi }}</td>	
								<td align="center">{{ $summary->planogram }}</td>																
								<td align="center">
									{!! link_to_action('UserSummaryReportController@show', 'View Stores', ['audit_id' => $summary->audit_id, 'user_id' => $summary->user_id], ['class' => 'btn btn-xs btn btn-primary']) !!}
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
$('#audits').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });

 $('#users').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

}).on("change", function(){			
	$.ajax({
		type:"POST",
		data: {users: GetSelectValues($('select#users :selected'))},
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
 $('#dt-table').dataTable();
@endsection