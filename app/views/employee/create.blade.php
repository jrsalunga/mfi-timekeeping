@extends('admin.masterfiles.index')

@section('title')
Employee - Add
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
    	<div>
    	</div>
    	<div>

    	</div>
    </div>
@stop

@section('modal')
<div class="row">
		<!-- Modal -->
		<div class="modal fade in" id="EmployeeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="display: block;">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		        <h4 class="modal-title" id="myModalLabel">Create New Record - Employee</h4>
		      </div>
		      <div class="modal-body">
		    		{{ Form::open(array(
		    			'url'=>'employee'
		    			)) 
		    		}}

		    		{{ Form::close() }}
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary">Save changes</button>
		      </div>
		    </div>
		  </div>
		</div>
</div>
@stop
<div class="modal-backdrop fade in"></div>