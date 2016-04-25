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
                            {!! link_to_route('audits.uploadosatargets','Upload OSA Targets',array($audit),['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">OSA Target Lists</h3>
                                    <h5 class="pull-right">{{ $osalookups->count() }} {{str_plural('record', $osalookups->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Region</th>
                                                <th>Distributor</th>
                                                <th>Template</th>
                                                <th>Store</th>
                                                <th colspan="2">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($osalookups->count() > 0)
                                            @foreach($osalookups as $lookup)
                                            <tr>
                                                <td>{{ $lookup->customer() }}</td>
                                                <td>{{ $lookup->region() }}</td>
                                                <td>{{ $lookup->distributor() }}</td>
                                                <td>{{ $lookup->channel() }}</td>
                                                <td>{{ $lookup->store() }}</td>
                                                <td>
                                                    {!! link_to_route('audits.osatargets_details','View Details',array('audit' => $audit, 'id' => $lookup->id),['class' => 'btn btn-xs btn-primary']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::open(array('method' => 'DELETE', 'action' => array('AuditOsaTargetController@destroy', $lookup->id), 'class' => 'disable-button')) !!}                       
                                                    {!! Form::submit('Delete', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to delete this record?')){return false;};")) !!}
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="7">No record found.</td>
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