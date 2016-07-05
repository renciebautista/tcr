@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-6 col-xs-6">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">List of Fields Tagged</h3>
				  	<div class="pull-right">{!! link_to_route('users.managefields_create','Tag Field',[$user['id']],['class' => 'btn btn-primary']) !!}</div>
				</div>
				
				  	<div class="box-body">
						<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Fullname</th>
								<th>Username</th>								
								<th>Status</th>		
								<th>Action</th>						
							</tr>
						</thead>
						<tbody>							
							@foreach($fields as $fd)								
								@if($fd->fdetails->active === 1)
								<tr>
								@elseif($fd->fdetails->active === 0)
									<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
								@endif
									<td>{{ $fd->fdetails->name }}</td>
									<td>{{ $fd->fdetails->username }}</td>							
									@if($fd->fdetails->active === 1)
										<td>Active											
									@else
										<td>In-active</td>
									@endif	
									<td>{{ Form::open(array('route' => array('users.managefieldsupdate'))) }}                       
										{{ Form::hidden('manager_id',$user->id)}}
										{{ Form::hidden('fields_id',$fd->fdetails->id)}}
										{{ Form::submit('Untag', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to untag this field?')){return false;};")) }}
										{{ Form::close() }}						
									</tr>																
							@endforeach					
						</tbody>
					</table>								  					  					
				  	</div>
				 	<div class="box-footer">						
				  	</div>								
			  </div>
		</div>
		<div class="col-md-6 col-xs-6">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">List of Templates Tagged</h3>
				  	<div class="pull-right">{!! link_to_route('users.managefields_template_create','Tag Template',[$user['id']],['class' => 'btn btn-primary']) !!}</div>
				</div>				
				  	<div class="box-body">		
				  	<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Code</th>
								<th>Description</th>								
								<th>Status</th>		
								<th>Action</th>						
							</tr>
						</thead>
						<tbody>							
							@foreach($templates as $tem)								
								@if($tem->tdetails->active === 1)
								<tr>
								@elseif($tem->tdetails->active === 0)
									<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
								@endif
									<td>{{ $tem->tdetails->code }}</td>
									<td>{{ $tem->tdetails->description }}</td>							
									@if($tem->tdetails->active === 1)
										<td>Active											
									@else
										<td>In-active</td>
									@endif	
									<td>{{ Form::open(array('route' => array('users.managefieldstemplateupdate'))) }}                       
										{{ Form::hidden('manager_id',$user->id)}}
										{{ Form::hidden('templates_id',$tem->tdetails->id)}}
										{{ Form::submit('Untag', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to untag this template?')){return false;};")) }}
										{{ Form::close() }}						
									</tr>																
							@endforeach					
						</tbody>
					</table>								  						  					  				
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
