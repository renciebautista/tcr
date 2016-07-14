@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">Export Audit Template</h3>
				</div>
				<div class="box-footer">
					{!! link_to_route('export_template_now','Export Now','',['class' => 'btn btn-primary']) !!}					
				</div>
		  </div>
		</div>
	</div>
</section>

@endsection
