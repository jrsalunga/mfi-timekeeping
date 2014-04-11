<?php

class TimelogController extends BaseController {
	
	
	private $rules = array(
			'employeeid'	=> 'required',
			'date'      	=> 'required',
			'time'			=> 'required',
			'txncode'    	=> 'required',
			'entrytype'    	=> 'required',
			'terminalid'	=> 'required',
	);
	
	
	public function index() {
		
		$vtimelog = vTimelog::paginate(10);
		//$vtimelog = Employee::orderBy('lastname', 'ASC')->paginate(10);
		
		return View::make('timelog.index')->with('timelogs', $vtimelog);
		
	}
	
	
	public function create(){
		
		$rules = array(
			'employeeid'	=> 'required',
			'date'      	=> 'required',
			'time'			=> 'required',
			'txncode'    	=> 'required',
			'entrytype'    	=> 'required',
			'terminalid'	=> 'required',
		);
		
		$validation = Validator::make(Input::all(), $rules);
		
		if ($validation->fails()) {
		
			//Session::flash('erms',  $validation->messages());
			//return Redirect::route('timelog.index');
			return Redirect::route('timelog.index')->withErrors($validation)->withInput();
			//return Redirect::to('admin/transactions/timelog');

		} else {
			
			$timelog = new Timelog;
			$timelog->employeeid    = Input::get('employeeid');
			$timelog->datetime 		= Input::get('date').' '.Input::get('time');
			$timelog->txncode 	 	= Input::get('txncode');
			$timelog->entrytype  	= Input::get('entrytype');
			$timelog->terminalid 	= Input::get('terminalid');
			$timelog->id 	 	 	= Timelog::get_uid();
			
			if($timelog->save()){
				Session::flash('message', 'Timelog saved!');
				return Redirect::route('timelog.index');
			} else {
				Session::flash('error', 'Error on saving!');
				return Redirect::route('timelog.index');
			}
			
			
		}
		
		
		//return Redirect::route('timelog.index');
		
		//Session::flash('message', 'Timelog saved!');
		//return Redirect::route('timelog.index');
		
	}
	
	
	public function update(){
		$flag = FALSE;
		$timelog = Timelog::find(Input::get('id'));
		
		foreach($timelog['attributes'] as $key => $val){
			if($key == 'datetime'){
				if($val != Input::get('date').' '.Input::get('time')){
					$timelog->datetime = Input::get('date').' '.Input::get('time');	
					$flag = TRUE;
				}
			} else { 
				if(Input::get($key) != $val){
					$timelog->$key = Input::get($key);
					$flag = TRUE;
				}
			}
		}
		
		if($flag==TRUE){
			if($timelog->save()){
				Session::flash('message', 'Timelog updated!');
				return Redirect::route('timelog.index');
			} else {
				Session::flash('error', 'Error on updating!');
				return Redirect::route('timelog.index');
			}	
		} else {
			Session::flash('error', 'Nothing to update!');
			return Redirect::route('timelog.index');	
		}
		

	}
	
	
	public function remove(){
		
		
		
		$timelog = Timelog::find(Input::get('id'));

		$timelog->delete();
		Session::flash('message', 'Timelog deleted!');
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