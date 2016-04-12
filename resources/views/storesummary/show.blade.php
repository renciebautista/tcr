@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Store Summary Report</h3>
        </div>
        <div class="box-body">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>Store Code</td>
                        <td>{{ $store->store_code }}</td>
                    </tr>
                    <tr>
                        <td>Store Name</td>
                        <td>{{ $store->store_name }}</td>
                    </tr>
                    <tr>
                        <td>Audit Name</td>
                        <td>{{ $store->audit->description }}</td>
                    </tr>
                    <tr>
                        <td>Audit Template</td>
                        <td>{{ $store->template }}</td>
                    </tr>
                    <tr>
                        <td>User Name</td>
                        <td>{{ $store->user->name }}</td>
                    </tr>
                    
                    <tr>
                        <td>Perfect Store</td>
                        <td>{{ $store->isPerfectStore() }}</td>
                    </tr>
                    <tr>
                        <td>OSA %</td>
                        <td>{{ $store->osa }}%</td>
                    </tr>
                    <tr>
                        <td>NPI %</td>
                        <td>{{ $store->npi }}%</td>
                    </tr>
                    <tr>
                        <td>Planogram %</td>
                        <td>{{ $store->planogram }}%</td>
                    </tr>
                    
                  </tbody>
                </table>
            </div>
            <div class="col-xs-12">
                {!! link_to_action('UserSummaryReportController@show', 'Back', ['audit_id' => $store->audit_id, 'user_id' => $store->user_id], ['class' => 'btn btn-default']) !!}
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
                                <th></th>
                                @foreach($categories as $category)
                                <th class="center">{{ $category->category }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                 <td class="row-header bold">PERFECT STORE</td>
                            </tr>
                            @foreach($groups as $group)
                            <tr>
                                <td class="row-header bold">{{ $group->group_desc }}</td>
                                @foreach($categories as $category)
                                <td class="center">

                                    @if((isset($data[$category->category][$group->group_desc])) && ($data[$category->category][$group->group_desc] == '1'))
                                    <i class="fa fa-fw fa-check green"></i>
                                    @endif

                                    @if((isset($data[$category->category][$group->group_desc])) && ($data[$category->category][$group->group_desc] == '0'))
                                    <i class="fa fa-fw fa-close red"></i>
                                    @endif

                                    
                                </td>
                                @endforeach
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
</section>
@endsection