@extends('admin.master')

@section('title')
Home
@stop

@section('nav-bar-collapse')
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</button>
@stop

@section('nav-bar')
<ul class="nav navbar-nav">
    <li>{{ HTML::linkRoute('admin.masterfiles.index', 'Masterfiles') }}</li>
   	<li>{{ HTML::linkRoute('admin.transactions.index', 'Transactions') }}</li>
   	<li>{{ HTML::linkRoute('admin.reports.index', 'Reports') }}</li>
</ul>
@stop

@section('nav-bar-right')
<ul class="nav navbar-nav navbar-right"> 
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-cog"></span>
            <b class="caret"></b>
        </a>
            <ul class="dropdown-menu">
            	<li>{{ HTML::linkRoute('admin.settings', 'Settings') }}</li>
                <li>{{ HTML::linkRoute('admin.logout', 'Sign Out') }}</li>     
          </ul>
    </li>
</ul> 
@stop

@section('r-pane')
<h3>Access Denied!</h3>
@stop