@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">SOS Type Lists</h3>
					<h5 class="pull-right">{{ $sostypes->count() }} {{str_plural('record', $sostypes->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<tbody>
							<tr>
								<td>ID</td>
								<th>SOS Type</th>
								<th>Action</th>
							</tr>
							@foreach($sostypes as $sostype)
							<tr>
								<td>{{ $sostype->id }}</td>
								<td>{{ $sostype->sos }}</td>
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