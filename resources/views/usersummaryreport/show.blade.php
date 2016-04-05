@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">

	<div class="box box-default">
        <div class="box-header with-border">
          	<h3 class="box-title">User Detailed Report</h3>
        </div>
        <div class="box-body">
          	<div class="col-md-6">
          		<table class="table table-bordered">
                <tbody>
                <tr>
                  	<td>User</td>
                  	<td>Jeff Lim</td>
                </tr>
                <tr>
                  	<td>Audit Name</td>
                  	<td>March 2016</td>
                </tr>
                <tr>
                  	<td>Stores Mapped</td>
                  	<td>20</td>
                </tr>
                <tr>
                  	<td>Stores Audited</td>
                  	<td>4</td>
                </tr>
                <tr>
                  	<td>Perfect Stores</td>
                  	<td>1</td>
                </tr>
                <tr>
                  	<td>% Achievement</td>
                  	<td>25%</td>
                </tr>
                <tr>
                  	<td>Category Doors</td>
                  	<td>40</td>
                </tr>
                <tr>
                  	<td>% Achievement</td>
                  	<td>58%</td>
                </tr>

                
              </tbody></table>
          	</div>
        </div>

      </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
					<table class="table table-hover">
						<tbody>
							<tr>
								<th>Store Code</th>
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
								<td></td>
								<td>SSM Fairview</td>
								<td>March 2016</td>
								<td>No</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Audit Summary</a>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>SVI Fairview</td>
								<td>March 2016</td>
								<td>Yes</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Audit Summary</a>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>SMCO Zabarte</td>
								<td>March 2016</td>
								<td>No</td>
								<td>99%</td>
								<td>96%</td>
								<td>65%</td>
								<td>Thursday, March 17, 2016</td>
								<td>
									<a href="http://www.tcr.chasetech.com/auditreport/64/summary" class="btn btn-xs btn btn-primary">Audit Summary</a>
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