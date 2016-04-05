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
	            <div class="col-md-4">
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
	            <div class="col-md-4">
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

          	<div class="row">
	            <div class="col-md-4">
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
							<tr>
								<td>Jeff Lim</td>
								<td>SSM Fairview</td>
								<td>March 2016</td>
								<td>No</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Summary</a>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Details</a>
								</td>
							</tr>
							<tr>
								<td>Jeff Lim</td>
								<td>SVI Fairview</td>
								<td>March 2016</td>
								<td>Yes</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Summary</a>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Details</a>
								</td>
							</tr>
							<tr>
								<td>Jeff Lim</td>
								<td>SMCO Zabarte</td>
								<td>March 2016</td>
								<td>No</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Summary</a>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Download Details</a>
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