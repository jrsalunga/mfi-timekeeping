@extends('admin.index')

@section('title')
Masterfiles
@stop

@section('nav-bar-collapse')
@parent
@stop

@section('nav-bar')
@parent
@stop

@section('nav-bar-right')
@parent
@stop

@section('stage')
    <div class="col-sm-2 col-md-2 l-pane">
        <ul class="nav nav-pills nav-stacked">
            <li>{{ HTML::linkRoute('employee.index', 'Employee') }}</li>
            <li>{{ HTML::linkRoute('department.index', 'Department') }}<li>
        </ul>
    </div>
@stop

    @section('content')




