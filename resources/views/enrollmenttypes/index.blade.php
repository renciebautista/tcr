@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Enrollment Type Lists</h3>
					<h5 class="pull-right">{{ $enrollmenttypes->count() }} {{str_plural('record', $enrollmenttypes->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<td>ID</td>
								<th>Enrollment Type</th>
								<th>Target Default Value</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($enrollmenttypes as $enrollmenttype)
							<tr>
								<td>{{ $enrollmenttype->id }}</td>
								<td>{{ $enrollmenttype->enrollmenttype }}</td>
								<td>{{ $enrollmenttype->value }}</td>
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