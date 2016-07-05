@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row menu pull-right">
		<div class="col-xs-12">
			{!! link_to_route('templatemaintenance.create','New Template',array(),['class' => 'btn btn-primary']) !!}
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Template List</h3>
					<h5 class="pull-right">{{ $templates->count() }} {{str_plural('record', $templates->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
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
							@foreach($templates as $template)
							@if($template->active === 1)
								<tr>
							@elseif($template->active === 0)
								<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
							@endif
								<td>{!!$template->code!!}</td>
								<td>{!!$template->description!!}</td>
								<td>{!!$template->getstatus()!!}</td>
								<td>@if($template->active===1)
									{!! link_to_route('templatemaintenance.edit', 'Edit', $template->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
									{!! link_to_route('templatemaintenance.updatestatus', 'Deactivate Template', [$template['id']], ['class' => 'btn btn-xs btn btn-danger','onclick' => "if(!confirm('Are you sure to deactivate this template?')){return false;};"]) !!}														
									@else
									{!! link_to_route('templatemaintenance.updatestatus', 'Activate Template', [$template['id']], ['class' => 'btn btn-xs btn btn-info','onclick' => "if(!confirm('Are you sure to activate this template?')){return false;};"]) !!}
									@endif																	
								</td>
							</tr>							
							@endforeach
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
</section>

@endsection