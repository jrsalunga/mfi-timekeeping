@extends('admin.masterfiles.index')

@section('title')
Department
@endsection




@section('l-pane')
    <div class="col-sm-2 col-md-2 l-pane">
    	{{ MenuManger::getNavs() }}
        {{--
        <ul class="nav nav-pills nav-stacked">
            <li>{{ HTML::linkRoute('employee.index', 'Employee') }}</li>
            <li class="active">{{ HTML::linkRoute('department.index', 'Department') }}<li>
        </ul>
        --}}
    </div>
@stop

@section('r-pane')
    <div class="col-sm-10 col-md-10 r-pane pull-right">
        Department
    </div>
@stop