@extends('admin.transactions.index')

@section('title')
Employee
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
    

       
    	<div>

    	<ul class="pager">
  			<li class="previous"><a href="#" data-toggle="modal" data-target="#EmployeeModal">Add Timelog</a></li>
		</ul>

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
		        <h4 class="modal-title" id="myModalLabel">Create New Record - Timelog</h4>
		      </div>
		      <div class="modal-body">
		    		{{ Form::open(array('id'=>'frm-mdl-timelog', 'name'=>'frm-mdl-timelog', 'class'=>'table-model form-horizontal', 'role'=>'form', 'data-table'=>'timelog')) }}

		    		<div class="form-group">
		    			{{ Form::label('name', 'Name:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">
							<!--<input id="code" class="form-control" type="text" required="" maxlength="20" placeholder="Code" name="code">-->
							<!-- {{ Form::text('code', '',array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Code')) }} -->
							<input type="text" class="form-control search-employee" role="searchfield" placeholder="Search employee" required>
							<input type="hidden" name="employeeid" id="emplpyeeid">
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('date', 'Date:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('date', '',array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'YYYY-MM-DD')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('time', 'Time:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('time', '',array('maxlength'=>'8', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'HH:MM:SS')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">

		    			{{ Form::label('txncode', 'Type:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::select('txncode', array('ti' => 'Time In', 'to' => 'Time Out'), 'ti', array('class'=>'form-control')) }}
							<input type="hidden" name="id" id="id">
							<input type="hidden" name="entrytype" id="entrytype" value="2">
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		

		    		{{ Form::close() }}
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" id="modal-btn-save">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
</div>
@stop



@section('document-ready')
<script type="text/javascript">
$(document).ready(function(){

	$('#modal-btn-save').on('click', function(){
		$('#frm-mdl-timelog').submit();
	});

});
@stop
