@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">List of Fields</h3>
				  	<div class="pull-right"><a href="javascript:history.back()"><button type="button" class="btn btn-default">Back</button></a></div>
				</div>

				{!! Form::open(array('route' => 'users.managefields_store')) !!}
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
							@foreach($users as $user)
								@if($user->role_name() == "field")									
									@if($user->active === 1)
									<tr>
									@elseif($user->active === 0)
										<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
									@endif
										<td>{{ $user->name }}</td>
										<td>{{ $user->username }}</td>								
										<td>{{ $user->getStatus() }}</td>
										<td>{{ Form::checkbox('tagfields[]',$user['id'])}}</td>										
									</tr>								
								@endif	
							@endforeach											
							<!-- <td>{!! Form::select('tagfields[]', $users, null, array('class' => 'form-control select_form', 'id' => 'tagfields', 'multiple' => 'multiple')) !!}</td>		 -->
						</tbody>
					</table>								  					  					
				  	</div>
				 	<div class="box-footer">
						<button type="submit" class="btn btn-success">Submit</button>						
				  	</div>	
				{!!Form::hidden('manager_id',$manager->id)!!}			  	
				{!! Form::close() !!}
			  </div>
		</div>		
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
