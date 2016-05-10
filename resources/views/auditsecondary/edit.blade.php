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
                {!! link_to_route('audits.secondarydisplay','Back',array('audit' => $audit->id),['class' => 'btn btn-default']) !!}
            </div>

          </div>
    </div>

    <div class="w-box">
        <div class="row">
            <div class="col-xs-12">
                <h4>Secondary Display Lookup </h4>
                <h5>{{ $store->store_code }} - {{ $store->store_name }}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('route' => array('audits.secondarydisplay_details','audit' => $audit->id, 'id' => $store->id),'method' => 'put')) !!}

                @foreach($categories as $category)
                <div class="form-group">
                    {!! Form::label(strtolower($category->category), $category->category); !!}
                    @foreach($category->brands as $brand)
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('brands[]', $brand->id, (in_array($brand->id,$selected) ? true : false)); !!} {{ $brand->brand }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @endforeach

                <button type="submit" class="btn btn-success">Update</button>
                {!! link_to_route('audits.secondarydisplay','Back',array('audit' => $audit->id),['class' => 'btn btn-default']) !!}

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection