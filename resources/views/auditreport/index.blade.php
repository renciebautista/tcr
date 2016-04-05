@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
        <div class="box-header with-border">
          	<h3 class="box-title">Posted Audit Report Filters</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>User</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">Jeff Lim</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Store Name</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">All Stores</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Audit Name</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">March 2016</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
	            <div class="col-md-3">
	              <div class="form-group">
	                <label>Perpect Store</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">All Status</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
          	</div>
        </div>

        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Process</button>
            <button type="submit" class="btn btn-success">Downlod</button>
        </div>
      </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<tbody>
							<tr>
								<th>User</th>
								<th>Store Name</th>
								<th>Audit Name</th>
								<th>Perfect Store</th>
								<th>OSA %</th>
								<th>NPI %</th>
								<th>Planogram %</th>
								<th>Posting Date</th>
								<th>Action</th>
							</tr>

							@foreach($audits as $audit)
							<tr>
								<td>{{ $audit->user->name }}</td>
								<td>{{ $audit->store_name }}</td>
								<td>{{ $audit->audit->description }}</td>
								<td>{{ $audit->isPerfectStore() }}</td>
								<td>{{ $audit->osa }}%</td>
								<td>{{ $audit->npi }}%</td>
								<td>{{ $audit->planogram }}%</td>
								<td>{{ $audit->updated_at }}</td>
								<td>
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