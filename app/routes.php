<?php



Route::get('/', function() {
	
	$sql = "SELECT employee.code, employee.lastname, employee.firstname, DATE(timelog.datetime) as date, ";
	$sql .= "TIME(timelog.datetime) as time, timelog.txncode as type ";
	$sql .= "FROM employee , timelog ";
	$sql .= "WHERE employee.id = timelog.employeeid ";
	$sql .= "ORDER BY DATE(timelog.datetime) DESC, TIME(timelog.datetime) DESC ";
	$sql .= "LIMIT 15";
	
	$employees = DB::select($sql);
	
	return View::make('home.index')->with('employees', $employees);
});



//Route::get('admin/masterfiles', 'MasterfilesController@index');
//Route::resource('admin', 'AdminController');
//Route::resource('admin/masterfiles', 'MasterfilesController');
//Route::resource('admin/reports', 'ReportsController');

Route::get('admin', array('before' => 'auth', 'as'=>'admin.index', 'uses'=>'AdminController@index'));

Route::get('admin/masterfiles', array('as'=>'admin.masterfiles.index', 'uses'=>'MasterfilesController@index'));
Route::get('admin/masterfiles/employee', array('as'=>'employee.index', 'uses'=>'EmployeeController@index'));
Route::post('admin/masterfiles/employee', array('as'=>'employee.create', 'uses'=>'EmployeeController@create', 'before' => 'csrf'));
//Route::get('admin/masterfiles/employee/add', array('as'=>'employee.create', 'uses'=>'EmployeeController@create'));
Route::get('admin/masterfiles/department', array('as'=>'department.index', 'uses'=>'DepartmentController@index'));



Route::get('admin/transactions', array('as'=>'admin.transactions.index', 'uses'=>'TransactionsController@index'));
Route::get('admin/transactions/timelog', array('as'=>'timelog.index', 'uses'=>'TimelogController@index'));
Route::post('admin/transactions/timelog', array('as'=>'timelog.create', 'uses'=>'TimelogController@create'));




Route::get('admin/reports', array('as'=>'admin.reports.index', 'uses'=>'ReportsController@index'));

Route::get('admin/reguser', array('as'=>'admin.reguser', 'uses'=>'AdminController@regUser'));
Route::get('admin/login', array('as'=>'admin.login', 'uses'=>'AdminController@login'));

Route::post('admin/login', array('as'=>'admin.login.post', 'uses'=>'AdminController@postLogin'));
/*
Route::post('admin/login', function(){
	$user = array(
		'email' => Input::get('email'),
		'password' => Input::get('password')
	);
	
	if (Auth::attempt($user)){
		return Redirect::to('profile');
	}
	return Redirect::to('admin/login')->with('login_error','Could not log in.');
});
*/

Route::get('admin/logout', array('as'=>'admin.logout', 'uses'=>'AdminController@logout'));
Route::get('admin/settings', array('as'=>'admin.settings', 'uses'=>'AdminController@settings'));

Route::post('api/timelog', array('as'=>'timelog.post', 'uses'=>'TimelogController@post'));
Route::get('api/employee/{field?}/{value?}', array('as'=>'field.get', 'uses'=>'EmployeeController@getByField'));


Route::get('emp', function(){
	$e = Employee::where('rfid', '=','0002357039')->get();
	$f = Employee::find('8C774A47A5C111E385D3C0188508F93C');
	return isset($e[0]) ? 'success':'error';
	
});


Route::get('select', function(){
	
	$sql  = "SELECT e.code, e.lastname, e.firstname, date(t.datetime) as date, time(t.datetime) as time, t.txncode as type ";
	$sql .= "FROM employee as a, timelog as b ";
	$sql .= "WHERE e.id = t.employeeid ";
	$sql .= "ORDER BY DATE(t.datetime) DESC, TIME(t.datetime) DESC ";
	$sql .= "LIMIT 20";
	
	$sql2 = "SELECT employee.code, employee.lastname, employee.firstname, DATE(timelog.datetime) as date, ";
	$sql2 .= "TIME(timelog.datetime) as time, timelog.txncode as type ";
	$sql2 .= "FROM employee , timelog ";
	$sql2 .= "WHERE employee.id = timelog.employeeid ";
	$sql2 .= "ORDER BY DATE(timelog.datetime) DESC, TIME(timelog.datetime) DESC ";
	$sql2 .= "LIMIT 20";
	
	$result = DB::select($sql2);
	return json_encode($result);
	
});


Route::get('timelogs', function(){
	//$timelogs = Timelog::paginate(5);	
	$timelogs = DB::table('timelog')->paginate(5);
	
	return $timelogs;
});

Route::get('employees', function(){
	//$timelogs = Timelog::paginate(5);	
	$employees = Employee::orderBy('lastname', 'ASC')->paginate(5);
	
	return $employees;
});


Route::get('emp-timelog', function(){

	//$timelogs = Employee::find('513FE18CA5C211E385D3C0188508F93C')->timelogs;
	
	$timelogs = Timelog::with('employee')->where('employeeid', '=', '513FE18CA5C211E385D3C0188508F93C')->get();
	
	//dd(Timelog::with('employee')->first()->toArray());
	
	//$timelogs = Employee::find('513FE18CA5C211E385D3C0188508F93C');
	return $timelogs;
});




Route::get('vtimelog', function(){
	//$timelogs = Timelog::paginate(5);	
	$vtimelog = vTimelog::paginate(10);
	//$vtimelog = Employee::orderBy('lastname', 'ASC')->paginate(10);
	
	return $vtimelog;
});















Event::listen('illuminate.query', function($query, $bindings, $time, $name){
	//var_dump($query);
	//var_dump($bindings);
	//var_dump($time);
	//var_dump($name);
});





























