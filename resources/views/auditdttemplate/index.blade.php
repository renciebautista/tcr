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
                            {!! link_to_route('audits.uploaddttemplates','Upload DT Audit Template',array($audit),['class' => 'btn btn-success']) !!}
                        </div>
                    </div>
                    
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">DT Audit Template Lists</h3>
                                    <h5 class="pull-right">{{ $templates->count() }} {{str_plural('record', $templates->count()) }} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Template Code</th>
                                                <th>Template Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($templates->count() > 0)
                                            @foreach($templates as $template)
                                            <tr>
                                                <td>{{ $template->channel_code }}</td>
                                                <td>{{ $template->description }}</td>
                                                <td>
                                                    {!! Form::open(array('method' => 'DELETE', 'action' => array('AuditDtTemplateController@destroy', $template->id), 'class' => 'disable-button')) !!}                       
                                                    {!! Form::submit('Delete', array('class'=> 'btn btn-danger btn-xs','onclick' => "if(!confirm('Are you sure to delete this record?')){return false;};")) !!}
                                                    {!! Form::close() !!}
                                                </td>
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