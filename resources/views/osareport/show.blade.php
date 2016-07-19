@extends('layouts.default')

@section('content')

@include('shared.notifications')

<section class="content">
	<div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">OSA Details</h3>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>Audit Month</td>
                        <td>{!!$details['description']!!}</td>
                    </tr>                    
                    <tr>
                        <td>Customer</td>
                        <td>{!!$details['customer']!!}</td>
                    </tr>
                    <tr>
                        <td>Audit Template</td>
                        <td>{!!$details['template']!!}</td>
                    </tr>                 
                    <tr>
                        <td>Category</td>
                        <td>{!!$details['category']!!}</td>
                    </tr>                 
                    <tr>
                        <td>SKU</td>
                        <td>{!!$details['prompt']!!}</td>
                    </tr>                                       
                  </tbody>
                </table>
            </div>
            <div class="col-xs-12">
                <a href="javascript:history.back()" ><button type="button" class="btn btn-default">Back</button></a>
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-padding">
				<div class="box-header with-border">
		          	<h3 class="box-title">Stores List</h3>
		          	<h5 class="pull-right">{{ count($osaStore) }} {{str_plural('record', count($osaStore))}} found.</h5>									
		        </div>									
				<div class="box-body table-responsive no-padding">
					<table id="dt-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
								<th>Store Name</th>								
							</tr>
						</thead>
						<tbody>														
							<?php $cnt = 1; ?>
							@foreach($osaStore as $osa)
							<tr>
								<td>{{ $cnt }}</td>
								<td>{{ $osa->store_name }}</td>								
							</tr>
							<?php $cnt++; ?>
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
$('#audits,#templates, #categories, #customers').multiselect({
 	maxHeight: 200,
    includeSelectAllOption: true,
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    buttonWidth: '100%',
	buttonClass: 'form-control',

 });
 $('#dt-table').dataTable();
@endsection
