@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Device Error Lists</h3>
					<h5 class="pull-right">{{ $devices->count() }} {{str_plural('record', $devices->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Device Id</th>
								<th>Last Reported</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($devices as $device)
							<tr>
								<td>
									{!! link_to_route('deviceerror.getfile', $device->filename, $device->filename) !!}

								</td>
								<td>{{ $device->updated_at}}</td>
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