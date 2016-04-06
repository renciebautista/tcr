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
                                    <h3 class="box-title">Audit Group Lists</h3>
                                    <h5 class="pull-right">{{ $groups->count() }} {{str_plural('record', $groups->count())}} found.</h5>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Group Description</th>
                                                <th class="text-center">SOS</th>
                                                <th class="text-center">Secondary Display</th>
                                                <th class="text-center">OSA</th>
                                                <th class="text-center">Custom</th>
                                                <th class="text-center">Perfect Store</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($groups->count() > 0)
                                            @foreach($groups as $group)
                                            <tr>
                                                <td>{{ $group->group_desc }}</td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("sos[$group->id]", '1', $group->sos, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("second_display[$group->id]", '1', $group->second_display, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("osa[$group->id]", '1', $group->osa, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("custom[$group->id]", '1', $group->custom, ['class' => 'chk-update']) !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! Form::checkbox("perfect_store[$group->id]", '1', $group->perfect_store, ['class' => 'chk-update']) !!}
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="6">No record found.</td>
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
        url: "{{ route('audits.groups_update', $audit->id) }}",
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