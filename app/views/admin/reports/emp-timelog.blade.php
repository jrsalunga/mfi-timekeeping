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
                      Search employee timelog:
                        <!--<h3 class="panel-title">Search employee timelog:</h3>-->
                    </div>
                    <div class="panel-body">
                        {{ Form::open(array('id'=>'emp-timelog', 'name'=>'emp-timelog', 'class'=>'table-model form-horizontal', 'role'=>'form', 'data-table'=>'timelog', 'method'=>'GET')) }}


                        <div class="form-group">
                            {{ Form::label('name', 'Name:', array('class'=>'col-sm-2 control-label')) }}
                            <div class="col-sm-10">
                                <input type="text" id="employee" class="form-control search-employee" role="searchfield" placeholder="Search employee" required value="{{{ isset($emp->lastname) ? $emp->lastname.', ' : '' }}}{{{ isset($emp->firstname) ? $emp->firstname : '' }}}">
                                <input type="hidden" name="employeeid" id="employeeid" value="{{{ isset($emp->id) ? $emp->id : '' }}}">
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
                              {{ Form::submit('Go', array('class'=>'btn btn-primary model-btn-save pull-right')); }}
                            </div>
                        </div>



                        {{ Form::close() }}
                    </div>
                </div>    
            </div>

            <div class="col-md-7">
                  <div style="text-align: right;">
                 @if (isset($timelogs))
                    <a href="{{ URL::full().'&export=csv' }}">
                    <span class="glyphicon glyphicon-export"></span>
                    CSV
                    </a>
                    &nbsp;
                    <a href="{{ URL::full().'&export=pdf' }}" target="_blank">
                    <span class="glyphicon glyphicon-download-alt"></span>
                    PDF
                    </a>
                 @endif
                  </div>
                <table class="table table-condensed">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                  </tr>
                </thead>
                <tbody class="tk-tb">
                    @if (isset($timelogs))
                        @foreach($timelogs as $timelog)
                        <tr
                          @if($timelog['day'] == 'Sun')
                              class="warning"
                          @endif
                        >
                          <td><em>{{ $timelog['day'] }} </em> {{ date('F j, Y', strtotime($timelog['date'])) }}</td>
                          <td title="{{ $timelog['in'] }}">{{ $timelog['ti'] }}</td>
                          <td title="{{ $timelog['out'] }}">{{ $timelog['to'] }}</td>
                      
                        </tr>
                        @endforeach
                    @else
                       
                    @endif

                </tbody>
            </table>
            </div>

        </div>   
    </div>
@stop





@section('document-ready')
<script type="text/javascript">
function searchEmployee(){
   $(".search-employee").autocomplete({
            source: function( request, response ) {
                $.ajax({
          type: 'GET',
          //url: "http://timekeeping.mfi.dev/api/search/employee",
          url: "{{ Request::root() }}/api/search/employee",
                    dataType: "json",
                    data: {
                        maxRows: 25,
                        q: request.term
                    },
                    success: function( data ) {
                        response( $.map( data, function( item ) {
                            return {
                                label: item.lastname + ', ' + item.firstname,
                                value: item.lastname + ', ' + item.firstname,
                id: item.id
                            }
                        }));
                    }
                });
            },
            minLength: 2,
            select: function( event, ui ) {
        //console.log(ui);
                //log( ui.item ? "Selected: " + ui.item.label : "Nothing selected, input was " + this.value);
  
        $("#employeeid").val(ui.item.id); /* set the selected id */
        
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        $("#employeeid").val('');  /* remove the id when change item */
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            },
      messages: {
        noResults: '',
        results: function() {}
      }
      
       });
}



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
  searchEmployee();

});
@stop