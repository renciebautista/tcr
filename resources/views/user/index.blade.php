@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="row menu pull-right">
		<div class="col-xs-12">
			{!! link_to_route('users.create','New User',array(),['class' => 'btn btn-primary']) !!}
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">User List</h3>
					<h5 class="pull-right">{{ $users->count() }} {{str_plural('record', $users->count())}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Fullname</th>
								<th>Username</th>
								<th>Group</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
							@foreach($users as $user)
							@if($user->active === 1)
								<tr>
							@elseif($user->active === 0)
								<tr class="danger" style="opacity:0.6; filter:alpha(opacity=40);">
							@endif
								<td>{{ $user->name }}</td>
								<td>{{ $user->username }}</td>
								<td>
									{{ $user->role_name() }}
								</td>
								<td>{{ $user->getStatus() }}</td>
								<td>									
								@if($user->active===1)
									{!! link_to_route('users.edit', 'Edit', $user->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
									{!! link_to_route('users.updatestatus', 'Deactivate User', [$user['id']], ['class' => 'btn btn-xs btn btn-danger','onclick' => "if(!confirm('Are you sure to deactivate this user?')){return false;};"]) !!}
															
								@else
									{!! link_to_route('users.updatestatus', 'Activate User', [$user['id']], ['class' => 'btn btn-xs btn btn-info','onclick' => "if(!confirm('Are you sure to activate this user?')){return false;};"]) !!}
								@endif
								@if($user->role_name() == "manager")
									{!! link_to_route('users.managefields', 'Manage Fields', [$user['id']], ['class' => 'btn btn-xs btn btn-info']) !!}
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