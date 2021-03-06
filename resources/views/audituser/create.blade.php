@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Upload PJP Target</h3>
				</div>
				{!! Form::open(array('route' => array('audits.postuploadpjptarget', $audit->id),'files' => true)) !!}
				  	<div class="box-body">
		                <div class="form-group">
					    	{!! Form::file('file','',array('id'=>'','class'=>'')) !!}
					  	</div>
	              	</div>

				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Submit</button>
						{!! link_to_route('audits.users','Back',array('id' => $audit->id),['class' => 'btn btn-default']) !!}
				  	</div>

				{!! Form::close() !!}
			  </div>
		</div>
	</div>
</section>


@endsection
