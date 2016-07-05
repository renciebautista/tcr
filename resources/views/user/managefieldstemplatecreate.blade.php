@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
				  	<h3 class="box-title">List of Templates</h3>
				  	<div class="pull-right"><a href="javascript:history.back()"><button type="button" class="btn btn-default">Back</button></a></div>
				</div>

				{!! Form::open(array('route' => 'users.managefields_template_store')) !!}
				  	<div class="box-body">
						<table class="table table-hover table-striped">
						<thead>
							<tr>								
								<th>Code</th>
								<th>Description</th>								
								<th>Status</th>		
								<th><input type="checkbox" name="checkAll" id="checkAll"> Select All</th>						
							</tr>
							<tr>								
								<th></th>
								<th></th>								
								<th></th>		
												
							</tr>
						</thead>
						<tbody>
							@foreach($templates as $template)
															
									@if($template->active === 1)
									<tr>
									@elseif($template->active === 0)
										<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
									@endif
										<td>{{ $template->code }}</td>
										<td>{{ $template->description }}</td>								
										<td>{{ $template->getStatus() }}</td>
										<td>{{ Form::checkbox('tagfields[]',$template['id'])}}</td>										
									</tr>																
							@endforeach																		
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
