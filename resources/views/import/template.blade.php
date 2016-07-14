@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Upload Template Mapping</h3>
				</div>
				{!! Form::open(array('route' => array('import_template_mapping.store'),'files' => true)) !!}
				  	<div class="box-body">
		                <div class="form-group">
					    	{!! Form::file('file','',array('id'=>'','class'=>'')) !!}
					  	</div>
	              	</div>

				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Submit</button>
						{!! link_to_route('users.index','Back','',['class' => 'btn btn-default']) !!}
				  	</div>

				{!! Form::close() !!}
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
