@extends('admin.transactions.index')

@section('title')
Employee Timelog
@endsection




@section('l-pane')
    <div class="col-sm-2 col-md-2 l-pane">
        <ul class="nav nav-pills nav-stacked">
            <li class="active">{{ HTML::linkRoute('timelog.index', 'Timelog') }}</li>
        </ul>
    </div>
@stop



@section('r-pane')
    <div class="col-sm-10 col-md-10 r-pane pull-right">
    	

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
    

    	

       
    	<div>

    	<ul class="pager">
  			<li class="previous"><a href="#" data-toggle="modal" data-target="#EmployeeModal">Add Timelog</a></li>
		</ul>

			<!--
    		<table class="table table-condensed">
  				<thead>
  					<tr>
	  					<th>Emp No</th>
	  					<th>Name</th>
	  					<th>Date</th>
	  					<th>Time</th>
	  					<th>Type</th>
	  					<th>Entry Type</th>
	  				<tr>
  				</thead>
  				<tbody>
  					@foreach($timelogs as $timelog)
  					<tr>
	  					<td>{{ $timelog->code }}</td>
	  					<td>{{ $timelog->lastname }}, {{ $timelog->firstname }} {{ $timelog->middlename }}</td>
	  					<td>{{ $timelog->date }}</td>
	  					<td>{{ $timelog->time }}</td>
	  					<td>
	  						@if ($timelog->type == 'ti')

	  						Time In

	  						@else

	  						Time Out

	  						@endif

	  					</td>
	  					<td>{{ $timelog->entrytype }}</td>
	  				</tr>
  					@endforeach
  				</tbody>
			</table>
			-->

			<table class="table table-condensed tb-timelog">
  				<thead>
  					<tr>
	  					<th>Emp No</th>
	  					<th>Name</th>
	  					<th>Date</th>
	  					<th>Time</th>
	  					<th>Type</th>
	  					<th>Entry Type</th>
	  				<tr>
  				</thead>
  				<tbody>
  				</tbody>
  			</table>


    	</div>
    	<div>

        {{ $timelogs->links() }} 

    	</div>
    </div>
@stop


@section('modal')
<div class="row">
		<!-- Modal -->
		<div class="modal fade" id="EmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">New Record: Timelog</h4>
		      </div>
			   	{{ Form::open(array('id'=>'frm-mdl-timelog', 'name'=>'frm-mdl-timelog', 'class'=>'table-model form-horizontal', 'role'=>'form', 'data-table'=>'timelog')) }}
			    {{ Form::hidden('_method', 'POST') }}
		      	<div class="modal-body">
		    		<div class="form-group">
		    			{{ Form::label('name', 'Name:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">
							<!--<input id="code" class="form-control" type="text" required="" maxlength="20" placeholder="Code" name="code">-->
							<!-- {{ Form::text('code', '',array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Code')) }} -->
							<input type="text" id="employee" class="form-control search-employee" role="searchfield" placeholder="Search employee" required>
							<input type="hidden" name="employeeid" id="employeeid">
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('date', 'Date:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('date', strftime("%Y-%m-%d", strtotime("now")) ,array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'YYYY-MM-DD', 'required')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('time', 'Time:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('time', strftime("%I:%M:%S", strtotime("now")),array('maxlength'=>'8', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'HH:MM:SS')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">

		    			{{ Form::label('txncode', 'Type:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::select('txncode', array('ti' => 'Time In', 'to' => 'Time Out'), 'ti', array('class'=>'form-control')) }}
							<input type="hidden" name="id" id="id">
							<input type="hidden" name="entrytype" id="entrytype" value="2">
							<input type="hidden" name="terminalid" id="terminalid" value="<?=$_SERVER['REMOTE_ADDR']?>">
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		      	</div>
		      	<div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary" id="modal-btn-save">Save changes</button>
		      	</div>
		      	{{ Form::close() }}
		    </div>
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

$(document).ready(function(){

	$( "#date" ).datepicker({
      dateFormat: 'yy-mm-dd',
    });

	

	var modalView = new ModalView({model: modalData, settings: modalSettings});

	$('.pager .previous a').on('click', function(){
		modalSettings.set({mode:'add', title: 'Add Record'});

		$('.table-model #employee').val('');
	});


	var pagination = new Pagination({{ $timelogs->toJson() }});
	var timelogs = new Timelogs(pagination.models[0].attributes.data);
	var timelogsView = new TimelogsView({collection: timelogs});
	timelogsView.render();

	searchEmployee();

});
@stop
