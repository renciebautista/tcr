@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
    
    <div class="w-box">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    {{ $audit->description }}
                </h2>
                <p>Date Range: {{ $audit->start_date }} - {{ $audit->end_date }}</p>
            </div>

             <div class="col-xs-12">
                {!! link_to_route('audits.users','Back',array($audit->id),['class' => 'btn btn-default']) !!}
            </div>
          </div>
    </div>

    <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
               @include('shared.audit_tab')
                <div class="tab-content">
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">{{ $user->name }} Store Lists</h3>
                                    <h5 class="pull-right">{{ $stores->count() }} {{str_plural('record', $stores->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>Store Code</th>
                                                <th>Store Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($stores->count() > 0)
                                            @foreach($stores as $store)
                                            <tr>
                                                <td>{{ $store->customer }}</td>
                                                <td>{{ $store->store_code }}</td>
                                                <td>{{ $store->store_name }}</td>
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