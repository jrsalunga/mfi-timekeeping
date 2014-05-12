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

@section('nav-bar')
<ul class="nav navbar-nav">
   
    <li>{{ HTML::linkRoute('admin.masterfiles.index', 'Masterfiles') }}</li>
    <li>{{ HTML::linkRoute('admin.transactions.index', 'Transactions') }}</li>
    <li class="active">{{ HTML::linkRoute('admin.reports.index', 'Reports') }}</li>
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
              <li><a href="#settings">Settings</a></li>
                <li><a href="../logout">Sign Out</a></li>     
          </ul>
    </li>
</ul> 
@stop


@section('l-pane')
    <div class="col-sm-2 col-md-2 l-pane">
        {{ MenuManger::getNavs() }}
        {{--
        <ul class="nav nav-pills nav-stacked">
            <li class="active">{{ HTML::linkRoute('reports.emptk', 'Employee Timelog') }}</li>
        </ul>
        --}}
    </div>
@stop

@section('r-pane')
    <div class="col-sm-10 col-md-10 r-pane pull-right">
        <div class="row">

          @if (Session::has('message'))
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              {{ Session::get('message') }}
            </div>
          @endif

          @if (Session::has('error'))
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              {{ Session::get('error') }}
            </div>
          @endif


          <?php $messages = $errors->all('<p>:message</p>') ?>

          @if($messages)
          <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              @foreach($messages as $message)
                {{ $message }}
              @endforeach
            </div>
          @endif
            
            <div class="col-md-5">
                <div class="panel panel-default tk-emp">
                    <div class="panel-heading">
                      Download employee timelogs
                        <!--<h3 class="panel-title">Search employee timelog:</h3>-->
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('id'=>'batch-timelog', 'name'=>'batch-timelog', 'class'=>'table-model form-horizontal', 'role'=>'form', 'data-table'=>'timelog', 'method'=>'GET')) }}

                        <div class="form-group">
                            {{ Form::label('paytype', 'Employees:', array('class'=>'col-sm-3 control-label')) }}
                            <div class="col-sm-9">           
                              <div class="btn-group" data-toggle="buttons">
                              <label class="btn btn-default">
                                <input type="radio" name="paytype" value="1"> Regular
                              </label>
                              <label class="btn btn-default">
                                <input type="radio" name="paytype" value="2"> Extra
                              </label>
                            </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            {{ Form::label('from', 'From:', array('class'=>'col-sm-2 control-label')) }}
                            <div class="col-sm-10">           
                              {{ Form::text('from', !is_null(Input::get('from')) ? Input::get('from') : strftime("%Y-%m-%d", strtotime("now")) ,array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'YYYY-MM-DD', 'required')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('to', 'To:', array('class'=>'col-sm-2 control-label')) }}
                            <div class="col-sm-10">           
                              {{ Form::text('to', strftime("%Y-%m-%d", strtotime("+1 day")) ,array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'YYYY-MM-DD', 'required')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            
                            <div class="col-sm-12">           
                              {{ Form::submit('Download', array('class'=>'btn btn-primary model-btn-save pull-right')); }}
                            </div>
                        </div>



                        {{ Form::close() }}
                    </div>
                </div>   

                
            </div>

            <div class="col-md-7">
                 
            </div>

        </div>   
    </div>
@stop





@section('document-ready')
<script type="text/javascript">




function daterange(){

  $( "#from" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#to" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#to" ).datepicker({
      defaultDate: "+1w",
      dateFormat: 'yy-mm-dd',
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
}

$(document).ready(function(){

  


  daterange();


});
@stop