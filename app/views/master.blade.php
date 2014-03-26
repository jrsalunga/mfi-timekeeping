<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="shortcut icon" type="image/x-icon" href="../images/mfi-logo.png" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
	<title>
	@section('title') 
	MFI Timekeeping -
	@show
	</title>

	{{ HTML::style('css/bootstrap.css') }}
	{{ HTML::style('css/styles-ui2.css') }}
	{{ HTML::style('css/style.css') }}
	
</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  	<div>
        <div class="navbar-header">

          	<a href="{{ URL::to('/admin') }}">
          		  {{ HTML::image('images/mfi-logo.png', 'MFI Logo', array('class'=>'img-responsive header-logo')) }}
            </a>
            <a class="navbar-brand" href="{{ URL::to('/admin') }}">ModularFusion Inc</a>
        </div>
        <div class="navbar-collapse collapse">           
	 
        </div>
    </div>
</div>
<!-- endixed navbar -->
<div class="container-tk-block">
	<div id="tk-block" class="row">
		<div class="main-l-pane col-sm-6 col-md-6">
			<div class="ts-group">
             	
        		<div class="ts">00:00:00</div>
            	<div class="am">AM</div>
            	<div style="clear: both;"></div>
               
			</div>
			<div class="date-group">
				<div id="date">
					<span class="glyphicon glyphicon-calendar"> </span>				
					<time>{{  date('F j, Y', strtotime('now')) }}</time>
					<span class="day">{{  date('D', strtotime('now')) }}</span>
				</div>
			</div>
			<div class="emp-group">
				<div class="img-cont">
					<img  id="emp-img" src="images/blank.jpg" height="140px" width="140px" >
				</div>
				<div class="emp-cont">
					<p id="emp-code"></p>
					<h1 id="emp-name"></h1>
					<p id="emp-pos"></p>
				</div>
				<div style="clear: both;"></div>
			</div>
			<div class="message-group"></div>
			
		</div>
		<div class="main-r-pane col-sm-6 col-md-6">
			<div class="container-table">
				<table class="table table-striped table-condensed" role="table">
					<thead>
						<tr>
							<th>Emp No</th><th>Name</th><th>Time</th><th>Type</th>
						</tr>
					</thead>
					<tbody class="emp-tk-list">
						@foreach($employees as $employee)
						<tr>
							<td>{{ $employee->code }}</td>
							<td>{{ $employee->lastname }}, {{ $employee->firstname }}</td>
							<td>{{ $employee->time }}</td>
							<td>{{ $employee->type == 'ti' ? 'Time In': 'Time Out' }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

	<div id="announce-block">
	
	</div>


<div class="modal fade" id="TKModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="myModalLabel">Good day!</h4>
      </div>
      <div class="modal-body">
      	<div class="emp-group">
				<div class="img-cont">
					<img  id="mdl-emp-img" src="" height="140px" width="140px" >
				</div>
				<div class="emp-cont">
					<p id="mdl-emp-code"></p>
					<h1 id="mdl-emp-name"></h1>
					<p id="mdl-emp-pos"></p>
				</div>
				<div style="clear: both;"></div>
			</div>
      </div>
      <div class="modal-footer">
      	<button type="button" id="btn-time-in" class="btn btn-primary" data-dismiss="modal">
      		press <strong>F</strong> for Time In
      	</button>
        <button type="button" id="btn-time-out" class="btn btn-warning" data-dismiss="modal">press <strong>J</strong> for Time Out</button>
        
      </div>
    </div>
  </div>
</div>


	{{ HTML::script('js/vendors/jquery-1.10.1.min.js') }}
	{{ HTML::script('js/vendors/jquery-ui-1.10.3.js') }}
	{{ HTML::script('js/vendors/moment.2.1.0-min.js') }}
	{{ HTML::script('js/vendors/jquery.typeflow.js') }}
	{{ HTML::script('js/vendors/bootstrap.min.js') }}
	{{ HTML::script('js/tk-main.js') }}


	
</body>
</html>