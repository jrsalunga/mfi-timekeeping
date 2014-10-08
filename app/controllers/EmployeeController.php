<?php

class EmployeeController extends BaseController {


	public function index() {
		//$employees = Employee::all();
		//$employees = Employee::orderBy('lastname', 'ASC')->get();
		
		//$employees = Employee::orderBy('lastname', 'ASC')->paginate(10);
		$q = Input::get('q');
		$rows = !is_null(Input::get('maxRow')) ? Input::get('maxRow') : 10;
		$sortBy = !is_null(Input::get('sortBy')) ? Input::get('sortBy') : 'lastname:asc';
		$sort = explode(':', $sortBy);

		$sort[1] = isset($sort[1]) ? $sort[1] : 'ASC';
		$sort[1] = (strcmp(strtoupper($sort[1]), 'ASC') || strcmp(strtoupper($sort[1]), 'DESC')) ? $sort[1] : 'ASC';

		/*
		if(is_null(Input::get('q'))){
			$employees = Employee::orderBy('lastname', 'ASC')->paginate($rows);
		} else {
			$q = Input::get('q');
			$employees = Employee::where('lastname', 'like', '%'.$q.'%')
									->where('firstname', 'like', '%'.$q.'%', 'OR')
									->where('middlename', 'like', '%'.$q.'%', 'OR')
									->where('code', 'like', '%'.$q.'%', 'OR')
									->orderBy('lastname', 'ASC')->paginate($rows);
		}
		*/

		$query = DB::table('employee');

		if(!is_null($q)){
			
			$query->where('lastname', 'like', '%'.$q.'%')
				  ->where('firstname', 'like', '%'.$q.'%', 'OR')
				  ->where('middlename', 'like', '%'.$q.'%', 'OR')
				  ->where('code', 'like', '%'.$q.'%', 'OR');	

		}
		

		$query->orderBy($sort[0], $sort[1]);
		$employees = $query->paginate($rows);

		if(!is_null(Input::get('q'))){
			$employees->appends(array('q' => Input::get('q')));
		}
		$employees->appends(array('sortBy' => $sort[0].':'.$sort[1]));

		$order = $sort[1];
		//echo $sortby.' - '.$order;
		
		//return View::make('employee.index')->with('employees', $employees);
		return View::make('employee.index', compact('employees', 'order', 'q'));
	}
	
	public function show() {
		return View::make('admin.masterfiles.index');
	}
	
	public function create() {

		$rules = array(
			'code'       => 'required',
			'lastname'   => 'required',
			'firstname'  => 'required',
			'middlename' => 'required'
		);
		
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			return Redirect::route('employee.index')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			// store
			$employee = new Employee;
			$employee->code      = Input::get('code');
			$employee->lastname	 = Input::get('lastname');
			$employee->firstname = Input::get('firstname');
			$employee->middlename = Input::get('middlename');
			$employee->position  = Input::get('position');
			$employee->rfid 	 = Input::get('rfid');
			$employee->paytype 	 = Input::get('paytype');
			$employee->processing 	 = Input::get('processing');
			$employee->id 	 	 = Employee::get_uid();
			
			
			try {
				$employee->save();
			}catch(\Exception $e){
				Session::flash('error', 'Error: '. $e->errorInfo[2]);
				return Redirect::route('employee.index');
			}
			

			// redirect
			Session::flash('message', 'Successfully created nerd!');
			return Redirect::route('employee.index');
		}
		/*
		$message = "Success: on saving";
		$employee = "";
		//return Redirect::route('employee.index')->with('message', $message);
		return View::make('employee.index')->with('data', array('message'=>$message, 'employee'=>$employee));
		//return View::make('employee.create');
		*/
	}
	
	public function update() {
		
		$employee = Employee::find(Input::get('id'));
		
		if($employee){
			$rules = array(
				'code'       => 'required',
				'lastname'   => 'required',
				'firstname'  => 'required',
				'middlename' => 'required'
			);	
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				return Redirect::route('employee.index')
					->withErrors($validator)
					->withInput(Input::except('password'));
			} else {
				$employee->code      = Input::get('code');
				$employee->lastname	 = Input::get('lastname');
				$employee->firstname = Input::get('firstname');
				$employee->middlename = Input::get('middlename');
				$employee->position  = Input::get('position');
				$employee->rfid 	 = Input::get('rfid');
				$employee->paytype 	 = Input::get('paytype');
				$employee->processing 	 = Input::get('processing');
				
				try {
					$employee->save();
				}catch(\Exception $e){
					Session::flash('error', 'Error: '. $e->errorInfo[2]);
					return Redirect::route('employee.index');
				}
				/*
				if($employee->save()){
					Session::flash('message', 'Success on updating employee!');
				} else {
					Session::flash('error', 'Error on saving!');
				}
				*/
				Session::flash('message', 'Success on updating employee!');
				return Redirect::route('employee.index');
			}
		} else {
			Session::flash('error', 'Employee not found!');
		}
		return Redirect::route('employee.index');
	}
	
	public function delete() {
		//return dd(Input::all());
		Session::flash('error', 'Unable to delete. Access denied! ');
		return Redirect::route('employee.index');
	}
	
	
	
	public function getByField($field, $value){
		
		
		
		$employee = Employee::where($field, '=', $value)->first();
		
		if($employee){
			$respone = array(
						'code'=>'200',
						'status'=>'success',
						'message'=>'Hello '. $employee->firstname. '=)',
						'data'=> $employee->toArray()
			);	
			
		} else {
			$respone = array(
						'code'=>'404',
						'status'=>'danger',
						'message'=>'Invalid RFID! Record no found.',
						'data'=> ''
			);	
		}
				
		return $respone;
	} 




	


}