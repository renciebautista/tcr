@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		@if($role->role_id === 4)
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
								<th><input type="checkbox" name="checkAll" id="checkAll"> Select All</th>					
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
										<!-- {{ Form::hidden('fields_id',$fd->fdetails->id)}} -->
										{{ Form::checkbox('tagfields[]',$fd->fdetails['id'])}}																							
									</tr>																
							@endforeach					
						</tbody>
					</table>								  					  					
				  	</div>
				 	<div class="box-footer">
				 	<p>With selected: {{ Form::submit('Untag', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to untag the selected field?')){return false;};")) }}</p>
				  	</div>	
				  	{{ Form::close() }}									
			  </div>
		</div>
		@elseif($role->role_id === 3)
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
				  	<div class="box-footer">
				 		<p>With selected: {{ Form::submit('Untag', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to untag the selected field?')){return false;};")) }}</p>
				  	</div>	
				  	{{ Form::close() }}									
			  </div>
		</div>
		@endif
	</div>
</section>

@endsection
@section('page-script')
$('#users, #customers,#tagfields,#stores,#status, #pjps').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
 $(function () {
    $("#checkAll").click(function () {
        if ($("#checkAll").is(':checked')) {
            $("input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });

        } else {
            $("input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });
});
@endsection
