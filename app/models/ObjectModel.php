<?php


class ObjectModel extends Eloquent {
	
	
	public static function get_uid(){
		$id = DB::select('SELECT UUID() as id');
		$id = array_shift($id);
		return strtoupper(str_replace("-", "", $id->id));
	}
	
	public static function uuid(){
		return md5(uniqid());
	}
	
	
	public static function searchField($q){
		 return self::where('code', 'like', '%'.$q.'%')
				->where('descriptor', 'like','%'.$q.'%', 'OR')
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
	

}