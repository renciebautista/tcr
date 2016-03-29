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