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
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>User</label>
	                {!! Form::select('users[]', $users, null, array('class' => 'form-control select_form', 'id' => 'users', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>Audit Name</label>
	               {!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
          	</div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Process</button>
            <button type="submit" class="btn btn-success">Downlod</button>
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
								<th>User</th>
								<th>Audit Name</th>
								<th class="center">Stores Mapped</th>
								<th class="center">Stores Visited</th>
								<th class="center">To be Visited</th>
								<th class="center">Perfect Stores</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($user_summaries as $summary)
							<tr>
								<td>{{ $summary->user_name }}</td>
								<td>{{ $summary->audit_description }}</td>
								<td class="center">{{ $summary->mapped_stores }}</td>
								<td class="center">{{ $summary->store_visited }}</td>
								<td class="center">{{ $summary->to_visited }}</td>
								<td class="center">{{ $summary->perfect_stores }}</td>
								<td>
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
$('#users,#audits').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
@endsection