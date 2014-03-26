<?php

class EmployeeController extends BaseController {


	public function index() {
		//$employees = Employee::all();
		//$employees = Employee::orderBy('lastname', 'ASC')->get();
		
		//$employees = Employee::orderBy('lastname', 'ASC')->paginate(10);
		
		$employees = Employee::orderBy('lastname', 'ASC')->paginate(10);

		//$employees->setBaseUrl('admin/masterfiles/employee');
		

		return View::make('employee.index')->with('employees', $employees);
	}
	
	public function show() {
		return View::make('admin.masterfiles.index');
	}
	
	public function create() {

		$rules = array(
			'code'       => 'required',
			'lastname'      => 'required',
			'firstname'      => 'required',
			'middlename'      => 'required'
		);
		
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			//return Redirect::to('nerds/create')
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
			$employee->id 	 	 = Employee::get_uid();
			$employee->save();

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