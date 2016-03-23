@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row menu pull-right">
		<div class="col-xs-12">
			{!! link_to_route('audits.create','New Audit',array(),['class' => 'btn btn-primary']) !!}
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Audit Lists</h3>
					<h2 class="pull-right">Total Record</h2>
					<div class="box-tools">
						<div class="input-group" style="width: 150px;">
							<input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
							<div class="input-group-btn">
								<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<tbody>
							<tr>
								<th>ID</th>
								<th>Description</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Action</th>
							</tr>
							@foreach($audits as $audit)
							<tr>
								<td>{{ $audit->id }}</td>
								<td>{{ $audit->description}}</td>
								<td>{{ date_format(date_create($audit->start_date),'m/d/Y')  }}</td>
								<td>{{ date_format(date_create($audit->end_date),'m/d/Y')  }}</td>
								<td>
									{!! link_to_route('audits.stores', 'Audit Details', $audit->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
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