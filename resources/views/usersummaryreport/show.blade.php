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
                  	<td>{{ $detail->user_name }}</td>
                </tr>
                <tr>
                  	<td>Audit Name</td>
                  	<td>{{ $detail->audit_description }}</td>
                </tr>
                <tr>
                  	<td>Stores Mapped</td>
                  	<td>{{ $detail->mapped_stores }}</td>
                </tr>
                <tr>
                  	<td>Stores Audited</td>
                  	<td>{{ $detail->store_visited }}</td>
                </tr>
                <tr>
                  	<td>Perfect Stores</td>
                  	<td>{{ $detail->perfect_stores }}</td>
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
          	 <div class="col-xs-12">
	            {!! link_to_route('usersummaryreport.index','Back',array(),['class' => 'btn btn-default']) !!}
	        </div>
        </div>

      </div>

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-body table-responsive no-padding">
          <table class="table table-hover table-striped">
            <thead>
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
            </thead>
						<tbody>
							
							@foreach($stores as $store)
							<tr>
								<td>{{ $store->store_code }}</td>
								<td>{{ $store->store_name }}</td>
								<td>{{ $store->audit->description }}</td>
								<td>{{ $store->isPerfectStore() }}</td>
								<td>{{ $store->osa }}%</td>
								<td>{{ $store->npi }}%</td>
								<td>{{ $store->planogram }}%</td>
								<td>{{ $store->updated_at }}</td>
								<td>
                  {!! link_to_action('StoreSummaryReportController@show', 'Store Summary', $store->id,  ['class' => 'btn btn-xs btn btn-primary']) !!}
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