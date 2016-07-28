@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('pjpreport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">PJP Frequency Report</h3>
        </div>
        <div class="box-body">
          	<div class="row">
          		<div class="col-md-3">
	            	<div class="form-group">
	                <label>User</label>
	               	{!! Form::select('users[]', $users, null, array('class' => 'form-control select_form', 'id' => 'users', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Month</label>
	                {!! Form::select('audits[]', $audits, null, array('class' => 'form-control select_form', 'id' => 'audits', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>	            	            
          	</div>
        </div>

        <div class="box-footer">
            <button type="submit" name="submit" value="process" class="btn btn-primary">Process</button>
            <button type="submit" name="submit" value="download" class="btn btn-success">Download</button>
        </div>
        {{  Form::close() }}

     </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h5 class="pull-right">{{ count($checkins) }} {{str_plural('record', count($checkins))}} found.</h5>
					
				</div><!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-hover table-striped">
						<thead>
							<tr>
								<th>Audit Month</th>
								<th>User</th>
								<th>Store Name</th>
								<th>Frequency</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($checkins as $checkin)
							<tr>
								<td>{{ $checkin->audit_month }}</td>
								<td>{{ $checkin->name }}</td>
								<td>{{ $checkin->store_name }}</td>
								<td>{{ $checkin->frequency }}</td>
								<td></td>
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

@section('page-script')
$('#audits').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
$('#users').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

}).on("change", function(){			
	$.ajax({
		type:"POST",
		data: {users: GetSelectValues($('select#users :selected'))},
		url: "../pjpreport/monthfilter",
		success: function(data){			
			$('select#audits').empty();
			$.each(data, function(i, text) {
				$('<option />',{value: i, text: text}).appendTo($('select#audits'));
			});
		$('select#audits').multiselect('rebuild');
		}
	});
});
$('#dt-table').dataTable();
@endsection
