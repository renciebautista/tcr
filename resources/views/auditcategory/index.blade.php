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
                                    <h3 class="box-title">Audit Category Lists</h3>
                                    <h5 class="pull-right">{{ $categories->count() }} {{str_plural('record', $categories->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category Description</th>
                                                <th class="text-center">Secondary Display</th>
                                                <th class="text-center">SOS</th>
                                                <th class="text-center">OSA</th>
                                                <th class="text-center">NPI</th>
                                                <th class="text-center">Planogram</th>
                                                <th class="text-center">Custom</th>
                                                <th class="text-center">Perfect Store</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($categories->count() > 0)
                                            @foreach($categories as $category)
                                            <tr>
                                                <td>{{ $category->category }}</td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("second_display[$category->id]", '1', $category->second_display, ['class' => 'chk-update']) !!}
                                                </td>
                                                
                                                <td class="text-center">
                                                    {!! Form::checkbox("sos[$category->id]", '1', $category->sos, ['class' => 'chk-update']) !!}
                                                </td>

                                                <td class="text-center">
                                                    {!! Form::checkbox("osa[$category->id]", '1', $category->osa, ['class' => 'chk-update']) !!}
                                                </td>
                                                
                                                <td class="text-center">
                                                    {!! Form::checkbox("npi[$category->id]", '1', $category->npi, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("plano[$category->id]", '1', $category->plano, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("custom[$category->id]", '1', $category->custom, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("perfect_store[$category->id]", '1', $category->perfect_store, ['class' => 'chk-update']) !!}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="8">No record found.</td>
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

@section('page-script')

$(".chk-update").change(function () {
    var value = 0;
    var name = $(this).attr('name');
    if($(this).is(":checked")){
        var value = 1;
    }
        
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "POST",
        url: "{{ route('audits.categories_update', $audit->id) }}",
        async: true,
        data: {
            method: "POST",
            name: name,
            value: value
        },
        success: function (msg) {
            
        }
    });
});

@endsection