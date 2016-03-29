@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
    @include('shared.audit_details')
    {!! Form::open(array('route' => array('audits.osatargetsupdate','audit' => $audit->id, 'id' => $lookup->id),'method' => 'put')) !!}
    {!! Form::hidden('audit_id', $audit->id); !!}
    <div class="w-box">
        <div class="row">
            <div class="col-xs-12">
                <h4>OSA Target Lookup </h4>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('customers', 'Customer'); !!}
                {!! Form::select('customers', array('0' => 'ALL CUSTOMERS') +$customers, $lookup->customer_code,['class' => 'form-control', 'id' => 'customers']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('regions', 'Region'); !!}
                {!! Form::select('regions', array('0' => 'ALL REGIONS') +$regions, $lookup->region_code,['class' => 'form-control', 'id' => 'regions']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('distributors', 'Distributor'); !!}
                {!! Form::select('distributors', array('0' => 'ALL DISTRIBUTORS') +$distributors, $lookup->distributor_code,['class' => 'form-control', 'id' => 'distributors']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('templates', 'Audit Template'); !!}
                {!! Form::select('templates', array('0' => 'ALL TEMPLATES') +$templates, $lookup->channel_code,['class' => 'form-control', 'id' => 'templates']) !!}
            </div>
            
        </div>
        
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('stores', 'Store'); !!}
                {!! Form::select('stores', array('0' => 'ALL STORES') +$stores,$lookup->store_code,['class' => 'form-control', 'id' => 'stores']) !!}
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-md-12">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Category</th>
                            <th>OSA Target</th>
                        </tr>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->category }}</td>
                            <td>{!! Form::text('target['.$category->id.']',isset($lookup->categories->where('form_category_id',$category->id)->first()->target) ? $lookup->categories->where('form_category_id',$category->id)->first()->target : "",['class' => 'form-control','placeholder' => 'Target']) !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        {!! link_to_route('audits.osatargets','Back',array('audit' => $audit->id),['class' => 'btn btn-default']) !!}

    </div>
    {!! Form::close() !!}

</div>

@endsection