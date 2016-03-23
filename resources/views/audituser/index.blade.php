@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
    <div class="w-box">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    {{ $audit->description }}
                    <small class="pull-right">Date Range: {{ $audit->start_date }} - {{ $audit->end_date }}</small>
                </h2>
            </div>
          </div>
    </div>
      
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
                                    <h3 class="box-title">Audit User Lists</h3>
                                    <h5 class="pull-right">{{ $users->count() }} {{str_plural('record', $users->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table id="store_table" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Fullname</th>
                                                <th>Username</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($users->count() > 0)
                                            @foreach($users as $user)
                                            <tr>
                                                <td>{{ $user->fullname }}</td>
                                                <td>{{ $user->username }}</td>
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