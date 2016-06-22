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
                            {!! link_to_route('audits.uploadpjptarget','Upload PJP Target',array($audit),['class' => 'btn btn-success']) !!}
                        </div>
                    </div>
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">Audit User Lists</h3>
                                    <h5 class="pull-right">{{ $users->count() }} {{str_plural('record', $users->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fullname</th>
                                                <th>Username</th>
                                                <th class="right">PJP Target</th>
                                                <th class="right">Mapped Stores</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($users->count() > 0)
                                            @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->username }}</td>
                                                <td class="right">{{ $user->target }}</td>
                                                <td class="right">
                                                    {!! link_to_route('audits.mappedstores','Mapped Stores',array('audit' => $audit, 'id' => $user->id),['class' => 'btn btn-xs btn-primary']) !!}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td >No record found.</td>
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