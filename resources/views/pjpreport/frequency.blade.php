@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('sosreport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">PJP Frequency Report</h3>
        </div>
        <div class="box-body">
          	
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
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Store Name</th>
								<th>Frequency</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($checkins as $checkin)
							<tr>
								<td>{{ $checkin->user_id }}</td>
								<td>{{ $checkin->store_name}}</td>
								<td>1</td>
								<td></td>
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
