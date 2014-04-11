<?php

class SearchController extends BaseController {


	public function index() {
		
		
		return Input::get('q');
		
		
	}
	
	
	//   for url 'api/search/{field?}'
	public function searchTable($table) {
	
		if (!$table){
		
		} else {
			
			$q = Input::get('q');
			$max =  Input::get('maxRows');
			$max = isset($max) ? $max : 25;
			
			$rules = array(
				'q'				=> 'required|alpha_dash',
				'maxRows'      	=> 'required|integer'
			);
			
			$validation = Validator::make(
				array('q'=> $q, 'maxRows'=> $max), 
				$rules
			);
			
			if($validation->fails()) {
				$results = array();
			} else {
				$results = Employee::searchField($q);
			}
		
			
		}
		
		
		
		
		return Response::json($results);
		
	}




	


}