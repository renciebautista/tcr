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
					<h3 class="box-title">Audit Month</h3>
					<h5 class="pull-right">{{ $audits->count() }} {{str_plural('record', $audits->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Description</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th class="text-center">Active</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($audits as $audit)
							<tr>
								<td>{{ $audit->id }}</td>
								<td>{{ $audit->description}}</td>
								<td>{{ date_format(date_create($audit->start_date),'m/d/Y')  }}</td>
								<td>{{ date_format(date_create($audit->end_date),'m/d/Y')  }}</td>
								<td class="text-center">
                                    @if($audit->active == 1)
                                    <i class="fa fa-fw fa-check"></i>
                                    @endif
                                </td>
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