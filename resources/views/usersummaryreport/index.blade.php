@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
        <div class="box-header with-border">
          	<h3 class="box-title">User Summary Report Filters</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>User</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">All Users</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>Audit Name</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">March 2016</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
          	</div>

        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Process</button>
            <button type="submit" class="btn btn-success">Downlod</button>
        </div>
      </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<tbody>
							<tr>
								<th>User</th>
								<th>Audit Name</th>
								<th>Stores Mapped</th>
								<th>Stores Visited</th>
								<th>To be Visited</th>
								<th>Perfect Stores</th>
								<th>Action</th>
							</tr>
							@foreach($user_summaries as $summary)
							<tr>
								<td>{{ $summary->user_name }}</td>
								<td>{{ $summary->audit_description }}</td>
								<td>{{ $summary->mapped_stores }}</td>
								<td>{{ $summary->store_visited }}</td>
								<td>{{ $summary->to_visited }}</td>
								<td>{{ $summary->perfect_stores }}</td>
								<td>
									{!! link_to_action('UserSummaryReportController@show', 'View Stores', ['audit_id' => $summary->audit_id, 'user_id' => $summary->user_id], ['class' => 'btn btn-xs btn btn-primary']) !!}
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Store List</a>
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