@extends('admin.masterfiles.index')

@section('title')
Employee
@endsection




@section('l-pane')
    <div class="col-sm-2 col-md-2 l-pane">
        <ul class="nav nav-pills nav-stacked">
            <li class="active">{{ HTML::linkRoute('employee.index', 'Employee') }}</li>
            <li>{{ HTML::linkRoute('department.index', 'Department') }}<li>
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
  			<li class="previous"><a href="#" data-toggle="modal" data-target="#EmployeeModal">Add Employee</a></li>
		</ul>

    		<table class="table table-condensed tb-employee">
  				<thead>
  					<tr>
	  					<th>Emp No</th>
	  					<th>Lastname</th>
	  					<th>Firstname</th>
	  					<th>Middlename</th>
	  					<th>Position</th>
	  					<th>RFID</th>
	  				<tr>
  				</thead>
  				<tbody>
  					<!--
  					@foreach($employees as $employee)
  					<tr>
	  					<td>{{ $employee->code }}</td>
	  					<td>{{ $employee->lastname }}</td>
	  					<td>{{ $employee->firstname }}</td>
	  					<td>{{ $employee->middlename }}</td>
	  					<td>{{ $employee->position }}</td>
	  					<td>{{ $employee->rfid }}</td>
	  				</tr>
  					@endforeach
  					-->
  				</tbody>
			</table>
    	</div>
    	<div>

        {{ $employees->links() }} 

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
		        <h4 class="modal-title" id="myModalLabel">Create New Record - Employee</h4>
		      </div>
		      	{{ Form::open(array('id'=>'frm-mdl-employee', 'name'=>'frm-mdl-employee', 'class'=>'table-model form-horizontal', 'role'=>'form', 'data-table'=>'employee')) }}
		      	{{ Form::hidden('_method', 'POST') }}
		      	<div class="modal-body">
		    		<div class="form-group">
		    			<!--<label class="col-sm-2 control-label" for="code">Code:</label>-->
		    			{{ Form::label('code', 'Code:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">
							<!--<input id="code" class="form-control" type="text" required="" maxlength="20" placeholder="Code" name="code">-->
							{{ Form::text('code', '',array('maxlength'=>'10', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Code')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('lastname', 'Lastname:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('lastname', '',array('maxlength'=>'30', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Lastname')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('firstname', 'Firstname:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('firstname', '',array('maxlength'=>'30', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Firstname')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('middlename', 'Middlename:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('middlename', '',array('maxlength'=>'30', 'required'=>'', 'class'=>'form-control', 'placeholder'=>'Middlename')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('position', 'Position:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('position', '',array('maxlength'=>'30', 'class'=>'form-control', 'placeholder'=>'Position')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>
		    		<div class="form-group">
		    			{{ Form::label('rfid', 'RFID:', array('class'=>'col-sm-2 control-label')) }}
						<div class="col-sm-10">						
							{{ Form::text('rfid', '',array('maxlength'=>'10', 'class'=>'form-control', 'placeholder'=>'RFID')) }}
							<span class="validation-error-block"></span>
						</div>
		    		</div>	    		
		      	</div>
		      	<div class="modal-footer">
		    		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        	<button type="button" class="btn btn-primary" id="modal-btn-save">Save changes</button>
		      	</div>
		      	{{ Form::close() }}
		    </div>
		  </div>
		</div>
</div>
@stop



@section('document-ready')
<script type="text/javascript">
$(document).ready(function(){

	$('.pager .previous a').on('click', function(){
		modalSettings.set({mode:'add', title: 'Add Record'});
		$(".table-model").clearForm();
		$(".table-model #code").focus();
	});




	var modalView = new ModalView({model: modalData, settings: modalSettings});
	var pagination = new Pagination({{ $employees->toJson() }});

	var tmpl = _.template('<td><%- code %>'
						+'<div class="tb-data-action">'					
						+'<a class="row-edit" href="#"><span class="glyphicon glyphicon-pencil"></span></a> '
						+'<a class="row-delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>'
						+'</div></td>'
						+'<td><%- lastname %></td>'
						+'<td><%- firstname %></td>'
						+'<td><%- middlename %></td>'
						+'<td><%- position %></td>'
						+'<td><%- rfid %></td>');
	var employees = new Employees(pagination.models[0].attributes.data);
	var tbDataTableView = new TBDataTableView({collection: employees, template: tmpl, el: '.tb-employee tbody',});

	//var employees = new Employees(pagination.models[0].attributes.data);
	//var employeesView = new EmployeesView({collection: employees});

	tbDataTableView.render();

});
@stop
