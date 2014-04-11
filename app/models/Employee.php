<?php


class Employee extends ObjectModel {
	
	protected $table = 'employee';
	public $timestamps = false;	
	public $incrementing = false;


	
	
	public function timelogs() {
        return $this->hasMany('Timelog', 'employeeid');
    }
	
	
	public static function searchField($q) {
		 return self::where('lastname', 'like', '%'.$q.'%')
				->where('firstname', 'like', '%'.$q.'%', 'OR')
				->get();	
	}
	
	
	public function getByPage($page = 1, $limit = 10){
	  $results = new StdClass;
	  $results->page = $page;
	  $results->limit = $limit;
	  $results->totalItems = 0;
	  $results->items = array();
	 
	  $users = $this->model->skip($limit * ($page - 1))
						   ->take($limit)
						   ->get();
	 
	  $result->totalItems = $this->model->count();
	  //$result->items = $users->all();
	  $result->items = static::all();
	 
	  return $result;
	}
	
	
	
	public function getTimelog(){
		
	}
	
	

}