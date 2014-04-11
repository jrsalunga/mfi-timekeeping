<?php



Route::get('/', function() {
	
	$sql = "SELECT employee.code, employee.lastname, employee.firstname, DATE(timelog.datetime) as date, ";
	$sql .= "TIME(timelog.datetime) as time, timelog.txncode as type, employee.rfid ";
	$sql .= "FROM employee , timelog ";
	$sql .= "WHERE employee.id = timelog.employeeid ";
	$sql .= "ORDER BY DATE(timelog.datetime) DESC, TIME(timelog.datetime) DESC ";
	$sql .= "LIMIT 20";
	
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
Route::post('admin/transactions/timelog', array('as'=>'timelog.create', 'uses'=>'TimelogController@create', 'before' => 'csrf'));
Route::put('admin/transactions/timelog', array('as'=>'timelog.update', 'uses'=>'TimelogController@update', 'before' => 'csrf'));
Route::delete('admin/transactions/timelog', array('as'=>'timelog.remove', 'uses'=>'TimelogController@remove', 'before' => 'csrf'));




Route::get('admin/reports', array('as'=>'admin.reports.index', 'uses'=>'ReportsController@index'));
Route::get('admin/reports/emp-timelog', array('as'=>'admin.reports.emptk', 'uses'=>'ReportsController@empTimelog'));



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
Route::get('api/search/{field?}', array('as'=>'search.field', 'uses'=>'SearchController@searchTable'));





Route::get('api/timelog/employee/{id?}', function($id = null) {
	
	if(!$id){
		return 'please select employee';
	}
	
	$to = Request::query('to');
	$fr = Request::query('fr');
	
	
	if(!empty($to) && !empty($fr)){
		
		if(strtotime($to) >= strtotime($fr)){
 			//return 'correct range';
		} else {
			return 'invalid range';
		}	
	} else {
		$query_date = 'now';
		// First day of the month.
		//$fr = date('Y-m-01', strtotime($query_date));
		// Last day of the month.
		//$to = date('Y-m-t', strtotime('now'));
		
		$fr = date('Y-m-d', strtotime('-14 day'));
		$to = date('Y-m-d', strtotime('now'));

	}
	
	

	if ($tl = Timelog::getEmpTimelogRange($id, $fr, $to)){
		return $tl;
	}
});





















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
	//return DB::getQueryLog();
	return $timelogs;
});




Route::get('vtimelog', function(){
	//$timelogs = Timelog::paginate(5);	
	$vtimelog = vTimelog::paginate(10);
	//$vtimelog = Employee::orderBy('lastname', 'ASC')->paginate(10);
	
	return $vtimelog;
});


Route::get('getbypage', function(){
	//$timelogs = Timelog::paginate(5);	
	$employee = new Employee;
	$data = $employee->getByPage(1, 5);
	
	//$vtimelog = Employee::orderBy('lastname', 'ASC')->paginate(10);
	
	return $data;
});



Route::get('emp-tito', function(){
	
	$timelogs = Timelog::with('employee')
					->where('employeeid', '=', '513FE18CA5C211E385D3C0188508F93C')
					->where('datetime', 'like', '2014-03-10%', 'AND')
					->where('txncode', '=', 'ti', 'AND')
					->orderBy('datetime', 'DESC')->get(); // ->first()
	return $timelogs;
});


Route::get('emp-tito2', function(){
	
	$timelogs = Timelog::employeeid('513FE18CA5C211E385D3C0188508F93C')
					->date('2014-03-10')
					->txncode('ti')
					->orderBy('datetime', 'DESC')->get(); // ->first()
	return $timelogs;
});


Route::get('getin', function(){
	
	$in = Timelog::getEmpIn('513FE18CA5C211E385D3C0188508F93C','2014-03-10');
	return $in;
});

Route::get('getins', function(){
	
	$in = Timelog::getEmpIns('513FE18CA5C211E385D3C0188508F93C','2014-04-01');
	return $in;
});

Route::get('getinout', function(){
	
	$in = Timelog::getEmpInOut('513FE18CA5C211E385D3C0188508F93C','2014-03-10');
	echo $in->ti.'<br>';
	echo $in->to;
});

 
Route::get('dt', function(){
	
	$fr = new DateTime(date('Y-m-d', strtotime('-14 day')));
	$to = new DateTime(date('Y-m-d', strtotime('now')));
	$to = $to->modify('+1 day');
	
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($fr, $interval ,$to);
	
	foreach($daterange as $date){
		$d = $date->format("Y-m-d");
		echo $d.'<br>';
	}
	
	
});


Route::get('daterange/{id?}', function($id = null) {
	
	if(!$id){
		return 'please select employee';
	}
	
	$to = Request::query('to');
	$fr = Request::query('fr');
	
	
	if(!empty($to) && !empty($fr)){
		
		if(strtotime($to) >= strtotime($fr)){
 			//return 'correct range';
		} else {
			return 'invalid range';
		}	
	} else {
		$query_date = 'now';
		// First day of the month.
		//$fr = date('Y-m-01', strtotime($query_date));
		// Last day of the month.
		//$to = date('Y-m-t', strtotime($query_date));
		
		$fr = new DateTime(date('Y-m-d', strtotime('now')));
		$to = $fr->modify('-15 days'); 
		
	}
	
	

	if ($tl = Timelog::getEmpTimelogRange($id, $fr, $to)){
		return $tl;
	}
});



Route::get('changeformat', function(){

	$tls = Timelog::where('txncode','=','to')->get();

	foreach($tls as $tl){
		$x = substr($tl->datetime,11,2);
		echo $x.'<br>';
		
		switch($x){
			case 1:
				$r = 13;
				break;
			case 2:
				$r = 14;
				break;
			case 3:
				$r = 15;
				break;
			case 4:
				$r = 16;
				break;
			case 5:
				$r = 17;
				break;
			case 6:
				$r = 18;
				break;
			case 7:
				$r = 19;
				break;
			case 8:
				$r = 20;
				break;
			case 9:
				$r = 21;
				break;
			case 10:
				$r = 22;
				break;
			/*
			case 11:
				$r = 23;
				break;
			case 12:
				$r = 24;
				break;	
			*/
			default:
				$r = $x;
		}
		
		$s = substr_replace($tl->datetime,$r, 11,2);
		echo $s.'<br>';
		
		/*
		$t = Timelog::find($tl->id);
		//echo $t.'<br>';
		$t->datetime = $s;
		
		if($t->save()){
			echo 'saved!<br>';
		} else {
			echo 'error!<br>';
		}
		*/
	
	}
	
	//return $tl->datetime;
});


Route::get('/pdf', function()
{
    $html = '<html><body>'
            . '<p>Put your html here, or generate it with your favourite '
            . 'templating system.</p>'
            . '</body></html>';
			$var = 'jeff';
    return PDF::load(View::make('emails.welcome')->with('var', $var) , 'A4', 'landscape')->show();
});














Event::listen('illuminate.query', function($query, $bindings, $time, $name){
	//var_dump($query);
	//var_dump($bindings);
	//var_dump($time);
	//var_dump($name);
});































