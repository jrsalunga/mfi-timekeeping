@extends('admin.master')

@section('title')
Reports
@stop

@section('nav-bar-collapse')
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
  <span class="sr-only">Toggle navigation</span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
  <span class="icon-bar"></span>
</button>
@stop

@section('navbar-collapse')
{{ MenuManger::getMenu(4) }}
@stop

@section('nav-bar')

@stop

@section('nav-bar-right')
{{--
<ul class="nav navbar-nav navbar-right"> 
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span>
            <b class="caret"></b>
        </a>
            <ul class="dropdown-menu">
              <li><a href="#settings">Settings</a></li>
                <li><a href="logout">Sign Out</a></li>     
          </ul>
    </li>
</ul> 
--}}
@stop


@section('l-pane')
    <div class="col-sm-2 col-md-2 l-pane">
        {{ MenuManger::getNavs() }}
        {{--
        <ul class="nav nav-pills nav-stacked">
            <li>{{ HTML::linkRoute('admin.reports.emptk', 'Employee Timelog') }}</li>
        </ul>
        --}}
    </div>
@stop

@section('r-pane')
    <div class="col-sm-10 col-md-10 r-pane pull-right">
        Reports
    </div>
@stop