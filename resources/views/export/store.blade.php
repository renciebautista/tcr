@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Export Store</h3>
				</div>
				<div class="box-footer">
					{!! link_to_route('export.export_xlsx','Export XLSX','',['class' => 'btn btn-primary']) !!}
					{!! link_to_route('export.export_csv','Export CSV','',['class' => 'btn btn-primary']) !!}			
				</div>
		  </div>
		</div>
	</div>
</section>

@endsection
@section('page-script')
	$("#start_date,#end_date").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
	$("#start_date,#end_date").datepicker({
		format: 'mm/dd/yyyy',
		calendarWeeks: true,
	    autoclose: true,
	    todayHighlight: true
	});
@endsection
