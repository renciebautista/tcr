@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
    @include('shared.audit_details')
    {!! Form::open(array('route' => array('audits.sostargetsupdate','audit' => $audit->id, 'id' => $lookup->id),'method' => 'put')) !!}
    {!! Form::hidden('audit_id', $audit->id); !!}
    <div class="w-box">
        <div class="row">
            <div class="col-xs-12">
                <h4>SOS Target Lookup </h4>
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
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Category</th>
                            <th>Less</th>
                            @foreach($sos_types as $sos)
                            <th>{{ $sos->sos }}</th>
                            @endforeach
                        </tr>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->category }}</td>
                            <td>{!! Form::text('category['.$category->id.'][0]',isset($lookup->categories->where('form_category_id',$category->id)->first()->less) ? $lookup->categories->where('form_category_id',$category->id)->first()->less : "" ,['class' => 'form-control numeric_input','placeholder' => 'Less']) !!}</td>
                            @foreach($sos_types as $sos)
                            <td>
                                {!! Form::text('category['.$category->id.']['.$sos->id.']',isset($lookup->categories->where('form_category_id',$category->id)->where('sos_type_id',$sos->id)->first()->value) ? $lookup->categories->where('form_category_id',$category->id)->where('sos_type_id',$sos->id)->first()->value : "",['class' => 'form-control numeric_input','placeholder' => $sos->sos]) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        {!! link_to_route('audits.sostargets','Back',array('audit' => $audit->id),['class' => 'btn btn-default']) !!}

    </div>
    {!! Form::close() !!}

</div>

@endsection