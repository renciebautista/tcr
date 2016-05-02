@extends('layouts.default')

@section('content')

@include('shared.notifications')
<section class="content">
      
    <div class="row">

        <div class="col-md-12">
          <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div class="tab-pane active" id="stores">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box-header">
                                    <h3 class="box-title">Template Category Lists</h3>
                                </div>
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Category Description</th>
                                                <th class="text-center">Secondary Display</th>
                                                <th class="text-center">OSA</th>
                                                <th class="text-center">SOS</th>
                                                <th class="text-center">Custom</th>
                                                <th class="text-center">Perfect Store</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
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



@endsection