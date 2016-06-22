@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
		{!! Form::open(array('route' => array('auditreport.create'), 'method' => 'POST')) !!}

        <div class="box-header with-border">
          	<h3 class="box-title">Posted Audit Report Filters</h3>
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
	                <label>Store Name</label>
	               	{!! Form::select('stores[]', $stores, null, array('class' => 'form-control select_form', 'id' => 'stores', 'multiple' => 'multiple')) !!}
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Name</label>
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
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th>User</th>
								<th>Store Name</th>
								<th>Audit Name</th>
								<th class="center" >Perfect Store</th>
								<th class="right">OSA %</th>
								<th class="right">NPI %</th>
								<th class="right">Planogram %</th>
								<th >Posting Date</th>
								<th class="center">Action</th>
							</tr>
						</thead>
						<tbody>
							

							@foreach($posted_audits as $audit)
							<tr>
								<td>{{ $audit->user->name }}</td>
								<td>{{ $audit->store_name }}</td>
								<td>{{ $audit->audit->description }}</td>
								<td class="center">{{ $audit->perfect_percentage }} %</td>
								<td class="right">{{ $audit->osa }}%</td>
								<td class="right">{{ $audit->npi }}%</td>
								<td class="right">{{ $audit->planogram }}%</td>
								<td>{{ $audit->updated_at }}</td>
								<td class="right">
									{!! link_to_route('auditreport.download', 'Download Details', $audit->id, ['class' => 'btn btn-xs btn btn-primary']) !!}
									{!! link_to_route('storesummaryreport.show', 'Store Summary', $audit->id, ['class' => 'btn btn-xs btn btn-primary']) !!}

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

@section('page-script')
$('#users,#audits,#stores,#status, #pjps').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
@endsection