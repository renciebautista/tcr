@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('usersummaryreport.create'), 'method' => 'POST')) !!}
        <div class="box-header with-border">
          	<h3 class="box-title">User Summary Report Filters</h3>
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
								@if($summary->target===0)
									<td align="center">cannot divide by zero</td>
								@else
									<td align="center">{{number_format(($summary->store_visited/$summary->target) * 100,2)}}</td>									
								@endif															
								<!-- <td class="right">{{ $summary->mapped_stores -  $summary->store_visited}}</td> -->
								<td align="center">{{ $summary->perfect_store }}</td>
								<td align="center">{{ number_format(($summary->perfect_store/$summary->store_visited) * 100,2)}}%</td>
								<td align="center"></td>								
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
$('#users,#audits, #pjps').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
 $('#dt-table').dataTable();
@endsection