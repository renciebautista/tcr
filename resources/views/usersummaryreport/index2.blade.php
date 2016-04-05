@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
        <div class="box-header with-border">
          	<h3 class="box-title">User Summary Report Filters</h3>
        </div>
        <div class="box-body">
          	<div class="row">
	            <div class="col-md-4">
	              <div class="form-group">
	                <label>User</label>
	                <select class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true">
	                  	<option selected="selected">All Users</option>
	                  	<option>Alaska</option>
	                  	<option>California</option>
	                  	<option>Delaware</option>
	                  	<option>Tennessee</option>
	                  	<option>Texas</option>
	                  <option>Washington</option>
	                </select>
	              </div>
	            </div>
	            <div class="col-md-4">
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
								<th>Audit Name</th>
								<th>Stores Mapped</th>
								<th>Stores Visited</th>
								<th>Perfect Stores</th>
								<th>To be Visited</th>
								<th>Action</th>
							</tr>
							<tr>
								<td>Jeff Lim</td>
								<td>March 2016</td>
								<td>20</td>
								<td>4</td>
								<td>1</td>
								<td>16</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Store List</a>
								</td>
							</tr>
							<tr>
								<td>Pauey Silva</td>
								<td>March 2016</td>
								<td>20</td>
								<td>4</td>
								<td>1</td>
								<td>16</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Store List</a>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	</div>
</section>

@endsection