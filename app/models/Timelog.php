<?php


class Timelog extends Eloquent {
	
	protected $table = 'timelog';
	public $timestamps = false;	
	
	
	public static function get_uid(){
		$id = DB::select('SELECT UUID() as id');
		$id = array_shift($id);
		return strtoupper(str_replace("-", "", $id->id));
	}
	
	public function employee()
    {
        return $this->belongsTo('Employee');
    }

}