@extends('layouts.default')

@section('content')

@include('shared.notifications')
    <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div align="center"><h1>403 - Not Authorized</h1></div>
                <div align="center"><p>You are not authorized to access this page</p></div>
            </div>
        </div>

@stop