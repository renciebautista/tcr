@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
    @include('shared.audit_details')
      
    <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
                @include('shared.audit_tab')
                <div class="tab-content">
                    <div class="row menu pull-right">
                        <div class="col-xs-12">
                            {!! link_to_route('audits.uploadstores','Upload Stores',array($audit),['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">Store Lists</h3>
                                    <h5 class="pull-right">{{ $stores->count() }} {{str_plural('record', $stores->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table id="store_table" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Customer</th>
                                                <th>Region</th>
                                                <th>Distributor</th>
                                                <th>Store Code</th>
                                                <th>Store</th>
                                                <th>Enrollment Type</th>
                                                <th>Field User</th>
                                                <th>Template</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($stores->count() > 0)
                                            @foreach($stores as $store)
                                            <tr>
                                                <td>{{ $store->account }}</td>
                                                <td>{{ $store->customer }}</td>
                                                <td>{{ $store->region }}</td>
                                                <td>{{ $store->distributor }}</td>
                                                <td>{{ $store->store_code }}</td>
                                                <td>{{ $store->store_name }}</td>
                                                <td>{{ $store->auditEnrollment->enrollmentType->enrollmenttype }}</td>
                                                <td>{{ $store->fielduser->name }}</td>
                                                <td>{{ $store->template }}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="8">No record found.</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection