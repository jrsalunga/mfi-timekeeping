<?php



Route::get('/', function() {
	
	$sql = "SELECT employee.code, employee.lastname, employee.firstname, employee.position, DATE(timelog.datetime) as date, ";
	$sql .= "TIME(timelog.datetime) as time, timelog.txncode as type, employee.rfid, timelog.replicated, timelog.id as timelogid ";
	$sql .= "FROM employee , timelog ";
	$sql .= "WHERE employee.id = timelog.employeeid ";
	$sql .= "ORDER BY DATE(timelog.datetime) DESC, TIME(timelog.datetime) DESC ";
	$sql .= "LIMIT 20";
	
	$employees = DB::select($sql);
	$first = $employees[0];
	
	return View::make('home.index', compact('first', 'employees'));
	//return View::make('home.index')->with('employees', $employees);
});



//Route::get('admin/masterfiles', 'MasterfilesController@index');
//Route::resource('admin', 'AdminController');
//Route::resource('admin/masterfiles', 'MasterfilesController');
//Route::resource('admin/reports', 'ReportsController');

Route::get('admin', array('before' => 'auth', 'as'=>'admin.index', 'uses'=>'AdminController@index'));

Route::get('admin/masterfiles', array('before' => 'auth-admin', 'as'=>'admin.masterfiles.index', 'uses'=>'MasterfilesController@index'));
Route::get('admin/masterfiles/employee', array('before' => 'auth-admin', 'as'=>'employee.index', 'uses'=>'EmployeeController@index'));
Route::post('admin/masterfiles/employee', array('before' => 'auth-admin', 'as'=>'employee.create', 'uses'=>'EmployeeController@create', 'before' => 'csrf'));
Route::put('admin/masterfiles/employee', array('before' => 'auth-admin', 'as'=>'employee.update', 'uses'=>'EmployeeController@update', 'before' => 'csrf'));
Route::delete('admin/masterfiles/employee', array('before' => 'auth-admin', 'as'=>'employee.delete', 'uses'=>'EmployeeController@delete', 'before' => 'csrf'));
//Route::get('admin/masterfiles/employee/add', array('as'=>'employee.create', 'uses'=>'EmployeeController@create'));
Route::get('admin/masterfiles/department', array('before' => 'auth-admin', 'as'=>'department.index', 'uses'=>'DepartmentController@index'));



Route::get('admin/transactions', array('before' => 'auth-admin', 'as'=>'admin.transactions.index', 'uses'=>'TransactionsController@index'));
Route::get('admin/transactions/timelog', array('before' => 'auth-admin', 'as'=>'timelog.index', 'uses'=>'TimelogController@index'));
Route::post('admin/transactions/timelog', array('before' => 'auth-admin', 'as'=>'timelog.create', 'uses'=>'TimelogController@create', 'before' => 'csrf'));
Route::put('admin/transactions/timelog', array('before' => 'auth-admin', 'as'=>'timelog.update', 'uses'=>'TimelogController@update', 'before' => 'csrf'));
Route::delete('admin/transactions/timelog', array('before' => 'auth-admin', 'as'=>'timelog.remove', 'uses'=>'TimelogController@remove', 'before' => 'csrf'));



Route::get('admin/reports', array('before' => 'auth', 'as'=>'admin.reports.index', 'uses'=>'ReportsController@index'));
Route::get('admin/reports/emp-timelog', array('before' => 'auth', 'as'=>'emp-timelog.emp-timelog', 'uses'=>'ReportsController@empTimelog'));
Route::get('admin/reports/batch-timelog', array('before' => 'auth', 'as'=>'batch-timelog.batch-timelog', 'uses'=>'ReportsController@empTimelogAll'));



Route::get('admin/reguser', array('as'=>'admin.reguser', 'uses'=>'AdminController@regUser'));
Route::get('admin/login', array('as'=>'admin.login', 'uses'=>'AdminController@login'));

Route::post('admin/login', array('as'=>'admin.login.post', 'uses'=>'AdminController@postLogin'));
Route::get('admin/restricted', array('before'=>'auth', 'as'=>'admin.restricted', 'uses'=>'AdminController@restricted'));
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
Route::post('api/replicate', array('as'=>'timelog.replicate', 'uses'=>'TimelogController@replicate'));
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








Route::get('register', function(){
	
	
	if(!empty($_GET['u']) && !empty($_GET['p'])){
		
		$user = new User();
		
		$user->name = Input::get('n');
		$user->username = Input::get('u');
		$user->password = Hash::make(Input::get('p'));
		$user->email = Input::get('e');
		$user->admin = '0';
		$user->id = Employee::get_uid();
		
		if($user->save()){
			return 'success';
		} else {
			return 'error';
		}
	} else {
		return 'no username & password';
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


Route::get('/controller', function() {
 	
	return Route::currentRouteAction();

});



Route::get('/timelog-api', function(){
    //$curl = new anlutro\cURL\cURL;
    //$url = $curl->buildUrl('http://htk.mfi.com/api/test', []);
    //$response = $curl->post($url, ['post' => 'data']);
    $timelog = new Timelog;
    $timelog->employeeid    = '10A782CFECEA11E28649235D6C08DF49';
    $timelog->datetime      = '2014-09-08 11:16:06';
    $timelog->txncode       = 'ti';
    $timelog->entrytype     = '1';
    $timelog->terminalid    = 'local';
    $timelog->id            = Timelog::get_uid();


    $url = cURL::buildUrl('http://htk.mfi.com/htk/api/timelog', array());
    $response = cURL::post($url, $timelog->toArray());

    echo $response->code.'<br>';
    echo $response->body.'<br>';
    echo json_encode($response->headers);
});















Event::listen('illuminate.query', function($query, $bindings, $time, $name){
	//var_dump($query);
	//var_dump($bindings);
	//var_dump($time);
	//var_dump($name);
});































