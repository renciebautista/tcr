@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Edit User</h3>
				</div>
				{!! Form::open(array('route' => array('users.update', $user->id), 'method' => 'PUT')) !!}
				  	<div class="box-body">
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('name', 'Fullname'); !!}
		                    	{!! Form::text('name',$user->name,['class' => 'form-control','placeholder' => 'Fullname','readonly']) !!}
							</div>
				  		</div>
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('username', 'Username'); !!}
		                    	{!! Form::text('username',$user->username,['class' => 'form-control','placeholder' => 'Username','readonly']) !!}
							</div>
				  		</div>
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('email', 'Email'); !!}
		                    	{!! Form::text('email',$user->email,['class' => 'form-control','placeholder' => 'Email']) !!}
							</div>
				  		</div>				  		
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('roles', 'Group'); !!}
          						{!! Form::select('role',$roles,$myrole, array('class' => 'form-control', 'placeholder' => 'Please Select')) !!}

							</div>
				  		</div>				  		
				  	</div>

				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Update</button>
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