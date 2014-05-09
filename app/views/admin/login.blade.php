@extends('admin.master')

@section('title')
Login
@endsection




@section('l-pane')
<div class="login-container img-rounded">
	<?= '<span style="color:red">' . Session::get('login_error') . '</span>' ?>
	<?= Form::open(array('role'=>'form')) ?>
    <?= Form::text('email', '', array('class'=>'form-control', 'placeholder'=>'Username')) ?>
    <?= Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password')) ?>
    <br>
    <?= Form::submit('Log In', array('class'=>'btn btn-lg btn-primary btn-block')) ?>
    <?= Form::close() ?>
    
</div>
@endsection