@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Edit Template</h3>
				</div>
				{!! Form::open(array('route' => array('templatemaintenance.update', $template->id), 'method' => 'PUT')) !!}
				  	<div class="box-body">
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('code', 'Code'); !!}
		                    	{!! Form::text('code',$template->code,['class' => 'form-control','placeholder' => 'Code']) !!}
							</div>
				  		</div>
				  		<div class="row">
				  			<div class="form-group col-md-6">
						   		{!! Form::label('description', 'Description'); !!}
		                    	{!! Form::text('description',$template->description,['class' => 'form-control','placeholder' => 'Description']) !!}
							</div>
				  		</div>				  		
				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Update</button>
						{!! link_to_route('templatemaintenance.index','Back',array(),['class' => 'btn btn-default']) !!}
				  	</div>
				{!! Form::close() !!}
			  </div>
		</div>
	</div>
</section>

@endsection
