@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">


	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Audit Name</th>
								<th>Store Name</th>
								<th>Category</th>
								<th class="right" >PS SOS Measurement</th>
								<th class="right" >Target</th>
								<th class="right" >Achievement</th>
							</tr>
						</thead>
						<tbody>
							
							@if(count($soss) > 0)
							<?php $cnt = 1; ?>
							@foreach($soss  as $sos)
							<tr>
								<td></td>
								<td>{{ $sos->store_name }}</td>
								<td>{{ $sos->category }}</td>
								<td class="right">{{ $sos->sos_measurement }}%</td>
								<td class="right"></td>
								<td class="center"></td>
							</tr>
							<?php $cnt++; ?>
							@endforeach
							@else
							<tr>
								<td colspan="6">No record found.</td>
							</tr>
							@endif
							
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
</section>

@endsection

@section('page-script')
$('#audits,#templates').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
@endsection