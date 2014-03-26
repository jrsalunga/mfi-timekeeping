<?php


class Employee extends Eloquent {
	
	protected $table = 'employee';
	public $timestamps = false;	
	public $incrementing = false;
	
	
	public static function get_uid(){
		$id = DB::select('SELECT UUID() as id');
		$id = array_shift($id);
		return strtoupper(str_replace("-", "", $id->id));
	}
	
	
	public function timelogs()
    {
        return $this->hasMany('Timelog', 'employeeid');
    }

}