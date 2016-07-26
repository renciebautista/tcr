@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Remap User</h3>
				</div>
				{!! Form::open(array('route' => 'remap.store')) !!}
				  	<div class="box-body">
				  		<div class="row">
				  			<div class="form-group col-md-6">				  				
						   		{!! Form::label('old_name', 'From'); !!}
		                    	{!! Form::select('old_name', $users, null, array('class' => 'form-control select_form', 'id' => 'users')) !!}
							</div>
							<div class="form-group col-md-6">
						   		{!! Form::label('new_name', 'To'); !!}
		                    	{!! Form::select('new_name', $users, null, array('class' => 'form-control select_form', 'id' => 'users')) !!}
							</div>
				  		</div>
				  	</div>
				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Submit</button>
						<a href="javascript:history.back()" ><button type="button" class="btn btn-default">Back</button></a>
				  	</div>
				{!! Form::close() !!}
				<div class="box-footer">							
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
