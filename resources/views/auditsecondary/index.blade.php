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
                            {!! link_to_route('audits.uploadsecondarydisplay','Upload Secondary Display',array($audit),['class' => 'btn btn-primary']) !!}
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
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Store Code</th>
                                                <th>Store Name</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($stores->count() > 0)
                                            @foreach($stores as $store)
                                            <tr>
                                                <td>{{ $store->store_code }}</td>
                                                <td>{{ $store->store_name }}</td>
                                                <td>{!! link_to_route('audits.secondarydisplay_details','View Details',array('audit' => $audit, 'id' => $store->audit_store_id),['class' => 'btn btn-xs btn-primary']) !!}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="3">No record found.</td>
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