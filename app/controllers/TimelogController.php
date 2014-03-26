<?php

class TimelogController extends BaseController {
	
	
	
	public function index() {
		
		$vtimelog = vTimelogs::paginate(10);
		//$vtimelog = Employee::orderBy('lastname', 'ASC')->paginate(10);
		
		return View::make('timelog.index')->with('timelogs', $vtimelog);
		
	}
	
	public function create(){
		Session::flash('message', 'Timelog saved!');
		return Redirect::route('timelog.index');
		
	}

	
	public function post() {
		

		$rules = array(
			//'employeeid'	=> 'required',
			'datetime'      => 'required',
			'txncode'      	=> 'required',
			'entrytype'     => 'required',
			'terminalid'    => 'required',
		);
		
		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails()) {
			
			$respone = array(
					'code'=>'400',
					'status'=>'error',
					'message'=>'Error on validation',
					'data'=> $validator
			);
			return json_encode('failed');
		} else {
			$employee = Employee::where('rfid', '=', Input::get('rfid'))->get();
			
			
			if(!isset($employee[0])){ // employee does not exist having the RFID submitted
				$respone = array(
						'code'=>'401',
						'status'=>'error',
						'message'=>'Invalid RFID: '.  Input::get('rfid'),
						'data'=> ''
				);	
			} else {
				$timelog = new Timelog;
			//$timelog->employeeid	= Input::get('employeeid');
			$timelog->employeeid    = $employee[0]->id;
			$timelog->datetime 		= Input::get('datetime');
			$timelog->txncode 	 	= Input::get('txncode');
			$timelog->entrytype  	= Input::get('entrytype');
			$timelog->terminalid 	= Input::get('terminalid');
			$timelog->id 	 	 	= Timelog::get_uid();
			
			$timelog->save();
		
			Session::flash('message', 'Successfully created nerd!');
			//return json_encode($employee[0]);
			
				$datetime = explode(' ',$timelog->datetime);
				$txncode = $timelog->txncode=='to' ? 'Time Out':'Time In';
			
				$data = array(
					'empno'		=> $employee[0]->code,
					'lastname'	=> $employee[0]->lastname,
					'firstname'	=> $employee[0]->firstname,
					'middlename'=> $employee[0]->middlename,
					'position'	=> $employee[0]->position ,
					'date'		=> $datetime[0] ,
					'time'		=> $datetime[1] ,
					'txncode'	=> $txncode
					
				);
			
				$respone = array(
						'code'=>'200',
						'status'=>'success',
						'message'=>'Record saved!',
						'data'=> $data
				);				
			}
			
			return json_encode($respone);
		}
	}




	


}