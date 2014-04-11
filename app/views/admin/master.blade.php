<!DOCTYPE HTML>
<html lang="en-ph">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Cache-control" content="public">

<title> 
	MFI Timekepping - @section('title') 
	@show
</title>

{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/styles-ui2.css') }}


</head>
<body>
<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  	<div>
        <div class="navbar-header">
            @section('nav-bar-collapse')
            @show
          	<a href="{{ URL::to('/admin') }}">
          		  {{ HTML::image('images/mfi-logo.png', 'MFI Logo', array('class'=>'img-responsive header-logo')) }}
            </a>
            <a class="navbar-brand" href="{{ URL::to('/admin') }}">Timekeeping Module</a>
        </div>
        <div class="navbar-collapse collapse">           
            @section('nav-bar')
            @show
            
            @section('nav-bar-right')
            @show		 
        </div>
    </div>
</div>
<div class="row">
    <div class="stage">
        @section('l-pane')
        @show

        @section('r-pane')
        @show
    </div>
</div> <!-- /container -->

@section('modal')
@show
	

	{{ HTML::script('js/vendors/jquery-1.10.1.min.js') }}
    {{ HTML::script('js/vendors/jquery-ui-1.10.3.js') }}
    {{ HTML::script('js/vendors/underscore-min.js') }}
    {{ HTML::script('js/vendors/backbone-min.js') }}
    {{ HTML::script('js/vendors/moment.2.1.0-min.js') }}
    {{ HTML::script('js/vendors/jquery.typeflow.js') }}
    {{ HTML::script('js/vendors/bootstrap.min.js') }}
    {{ HTML::script('js/models.js') }}
    {{ HTML::script('js/collections.js') }}
    {{ HTML::script('js/views.js') }}

@section('document-ready')
@show


</script>	
</body>
</html>