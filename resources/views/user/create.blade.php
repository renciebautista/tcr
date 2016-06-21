@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">New User</h3>
				</div>
				{!! Form::open(array('route' => 'users.store')) !!}
				  	<div class="box-body">
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('name', 'Fullname'); !!}
		                    	{!! Form::text('name',null,['class' => 'form-control','placeholder' => 'Fullname']) !!}
							</div>
				  		</div>

				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('username', 'Username'); !!}
		                    	{!! Form::text('username',null,['class' => 'form-control','placeholder' => 'Username']) !!}
							</div>
				  		</div>

				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('email', 'Email'); !!}
		                    	{!! Form::text('email',null,['class' => 'form-control','placeholder' => 'Email']) !!}
							</div>
				  		</div>

				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('password', 'Password'); !!}
		                    	{!! Form::password('password',['class' => 'form-control', 'placeholder' => 'Password']) !!}
							</div>
				  		</div>

				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('password_confirmation', 'Confirm Password'); !!}
		                    	{!! Form::password('password_confirmation',['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!}
							</div>
				  		</div>

				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('roles', 'Group'); !!}
          						{!! Form::select('role',$roles,null, array('class' => 'form-control', 'placeholder' => 'Please Select')) !!}
							</div>
				  		</div>


				  		<div class="row">
				  			<div class="form-group col-xs-6 col-md-3">
						   		<label>
				                  	{!! Form::checkbox('active', 1) !!} Active
				                  </label>
							</div>


				  		</div>

				  		
						
				  	</div>

				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Submit</button>
						{!! link_to_route('users.index','Back',array(),['class' => 'btn btn-default']) !!}
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
