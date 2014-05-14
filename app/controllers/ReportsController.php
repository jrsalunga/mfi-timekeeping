<?php

class ReportsController extends BaseController {


	public function index() {
		return View::make('admin.reports.index');
	}
	
	public function show() {
		return View::make('admin.reports.index');
	}
	
	
	
	public function empTimelog(){
		
		
		Validator::extend('daterange', function($attribute, $value, $parameters){
			return $value == 'daterange';
		});
		
		//echo dd()
		
		if(!is_null(Input::get('employeeid')) && !is_null(Input::get('from')) && !is_null(Input::get('to'))){
			
			
			$rules = array(
				'employeeid' => 'required',
				'from'		 => 'required:daterange',
				'to'      	 => 'required:daterange',
			);
			
			$validator = Validator::make(Input::all(), $rules);
	
			if ($validator->fails()) {
		
				return View::make('admin.reports.emp-timelog')
					->withErrors($validator);
			} else {
			
		
			
				$emp = Employee::find(Input::get('employeeid'));
				
				if(is_null($emp)){
					Session::flash('error', 'Employee not found!');
					return View::make('admin.reports.emp-timelog');
				}
			
			
				$name = $emp->lastname.', '.$emp->firstname;
				
				$timelogs = Timelog::getEmpTimelogRange(Input::get('employeeid'), Input::get('from') ,Input::get('to'));
				
				if(!is_null(Input::get('export')) && Input::get('export') == 'csv'){
					$this->exportCSV($emp, $timelogs);
				}
				
				if(!is_null(Input::get('export')) && Input::get('export') == 'pdf'){
					$this->exportPDF($emp, $timelogs);
				}
				
			
				return View::make('admin.reports.emp-timelog')->with('emp', $emp)->with('timelogs', $timelogs);
			}
		} else {
			
			return View::make('admin.reports.emp-timelog');
		}	
	}
	
	public function empTimelogAll(){
		
		if(!is_null(Input::get('from')) && !is_null(Input::get('to'))){
			
			if(is_null(Input::get('paytype'))){
				Session::flash('error', 'Please select employee type!');
				return View::make('admin.reports.batch-timelog');
			} else {
			 	if(in_array(Input::get('paytype'), array('1','2'))){
					$employees = Employee::where('paytype', '=', Input::get('paytype'))->orderBy('lastname')->get();
					$output = array();
					
					foreach($employees as $employee){
						
						$timelogs = Timelog::getEmpTimelogRange($employee->id, Input::get('from') ,Input::get('to'));
						
						
						
						array_push($output, array($employee->lastname .', '. $employee->firstname, '', '', 'rfid: '.$employee->rfid, '', ''));
						array_push($output, array('', '', '', '', '', ''));					
						array_push($output, array('Day', 'Date', 'In(24)', 'Out(24)', 'In(12)', 'Out(12)'));	
						
						foreach ($timelogs as $timelog) {
							unset($timelog['tis']);
							unset($timelog['tos']);
							
							array_push($output, $timelog);	
						}
						
						array_push($output, array('', '', '', '', '', ''));
						array_push($output, array('', '', '', '', '', ''));
						
					}
					
					//return $output;
					$name =  Input::get('paytype')==1 ? 'regular':'extra';
					return Excel::create($name.'-'.date('YmdHis', strtotime('now')))
					  ->sheet('alls')
					  ->with($output)
					  ->export('csv');
					  
					  
				} else {
					Session::flash('error', 'unkown paytype: '. Input::get('paytype'));
					return View::make('admin.reports.batch-timelog');
			
				}	
			}
			
			
		} else {
			return View::make('admin.reports.batch-timelog');	
		}
		
	}
	
	private function exportAllCSV($employees, $timelogs){
		
		$output = array();
		array_push($output, array('Day', 'Date', 'In(24)', 'Out(24)', 'In(12)', 'Out(12)'));	
		
		foreach ($timelogs as $timelog) {
			unset($timelog['tis']);
			unset($timelog['tos']);
			array_push($output, $timelog);	
		}

		return Excel::create($employee->lastname.','.$employee->firstname.'-'.date('YmdHis', strtotime('now')))
					  ->sheet($employee->lastname.','.$employee->firstname)
					  ->with($output)
					  ->export('csv');
	}
	
	
	
	
	
	private function exportCSV($employee, $timelogs){
		
		$output = array();
		array_push($output, array('Day', 'Date', 'In(24)', 'Out(24)', 'In(12)', 'Out(12)'));	
		
		foreach ($timelogs as $timelog) {
			unset($timelog['tis']);
			unset($timelog['tos']);
			array_push($output, $timelog);	
		}

		return Excel::create($employee->lastname.','.$employee->firstname.'-'.date('YmdHis', strtotime('now')))
					  ->sheet($employee->lastname.','.$employee->firstname)
					  ->with($output)
					  ->export('csv');
	}
	
	
	private function exportPDF($employee, $timelogs, $paper = 'A4', $orientation = 'portrait', $action = 'show'){
		if($action == 'download'){
			return PDF::load(
					View::make('pdf.emp-timelogs')
						->with('employee', $employee) 
						->with('timelogs', $timelogs) 
				, $paper, $orientation)->download($employee->lastname.','.$employee->firstname.'-'.date('YmdHis', strtotime('now')));
		} else {		
			return PDF::load(
					View::make('pdf.emp-timelogs')
						->with('employee', $employee) 
						->with('timelogs', $timelogs) 
				, $paper, $orientation)->show();
		}
	}

	


}