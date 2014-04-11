<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $employee->lastname }}, {{ $employee->firstname }}</title>


<style type="text/css">


.container {
	width: 50%;
	border: 0px solid #cccccc;

}

.profile {
	height: 100px;
	border: 0px solid #cccccc;
}



</style>
</head>

<body>


	<div class="container">
		<div class="profile">
			<h3>{{ $employee->lastname }}, {{ $employee->firstname }}</h3>
		</div>
		<table>
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
                	<tr>
                    	<td><em>{{ $timelog['day'] }} </em> {{ date('F j, Y', strtotime($timelog['date'])) }}</td>
                      	<td>{{ $timelog['ti'] }}</td>
                      	<td>{{ $timelog['to'] }}</td>                 
                    </tr>
                    @endforeach
                @else
                   
                @endif
            </tbody>
        </table>
	</div>

</body>
</html>