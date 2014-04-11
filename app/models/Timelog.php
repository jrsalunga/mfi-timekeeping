<?php


class Timelog extends Eloquent {
	
	protected $table = 'timelog';
	public $timestamps = false;
	public $incrementing = false;	
	
	private $rules = array(
		'employeeid'	=> 'required',
		'date'      	=> 'required',
		'time'			=> 'required',
		'txncode'    	=> 'required',
		'entrytype'    	=> 'required',
		'terminalid'	=> 'required'
	);
	
	public function validate($input) {
		return Validator::make($input, $this->rules);
	}
	
	
	public static function get_uid(){
		$id = DB::select('SELECT UUID() as id');
		$id = array_shift($id);
		return strtoupper(str_replace("-", "", $id->id));
	}
	
	public function employee() {
        return $this->belongsTo('Employee', 'employeeid');
    }
	
	
	
	public static function getEmpTimelogRange($employeeid, $from, $to){
		$tl = array();
		$begin = new DateTime($from);
		$end = new DateTime($to);
		$end = $end->modify('+1 day'); 
		
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		
		foreach($daterange as $date){
			$d = $date->format("Y-m-d");
			$day = date('D', strtotime($d));
			
			$atis =  array();
			$atos =  array();
			
			$ti = self::getEmpIn($employeeid, $d);
			$to = self::getEmpOut($employeeid, $d);
			
			if(!$ti){
				$tix = "-";
				$in = "-";
			} else {
				//$ti = explode(' ', $ti["attributes"]['datetime']);
				//$tix = $ti[1];
				$tix = strftime('%I:%M:%S %p', strtotime($ti["attributes"]['datetime']));
				$in = strftime('%H:%M:%S', strtotime($ti["attributes"]['datetime']));
			}
			
			if(!$to){
				$tox = "-";
				$out = "-";
			} else {
				//$to = explode(' ', $to["attributes"]['datetime']);
				//$tox = $to[1];
				$tox = strftime('%I:%M:%S %p', strtotime($to["attributes"]['datetime']));
				$out = strftime('%H:%M:%S', strtotime($to["attributes"]['datetime']));
			}
			
			
						
			$tis = self::getEmpIns($employeeid, $d);
			$tos = self::getEmpOuts($employeeid, $d);
			
			if(!$tis){
				
			} else {
				foreach($tis as $is){
					array_push($atis, strftime('%I:%M:%S', strtotime($is["attributes"]['datetime'])));
				}	
			}
			
			if(!$tos){
				
			} else {
				foreach($tos as $os){
					array_push($atos, strftime('%I:%M:%S', strtotime($os["attributes"]['datetime'])));
				}
			}
			
			array_push($tl, array('day'=> $day, 'date' => $d, 'in'=>$in, 'out'=>$out, 'ti'=>$tix, 'to'=>$tox, 'tis'=> $atis, 'tos'=> $atos));
		}
		
		return $tl;
		
	}
	
	
	public static function getEmpInOut($employeeid=NULL, $date=NULL, $order = 'DESC'){
		if(!is_null($employeeid) || !is_null($date)){
			$ti = self::getEmpIn($employeeid, $date, $order);
			$to = self::getEmpOut($employeeid, $date, $order);
			$ti = explode(' ', $ti->datetime);
			$to = explode(' ', $to->datetime);
			return (object) array('ti'=>$ti[1], 'to'=>$to[1]);
		}
	}
	
	public static function getEmpIn($employeeid=NULL, $date=NULL, $order = 'ASC'){
		if(!is_null($employeeid) || !is_null($date)){
			return self::employeeid($employeeid)->date($date)->txncode('ti')->orderBy('datetime', $order)->first(array('datetime'));
		} else {
			return false;	
		}
	}
	
	public static function getEmpOut($employeeid=NULL, $date=NULL, $order = 'DESC'){
		if(!is_null($employeeid) || !is_null($date)){
			return self::employeeid($employeeid)->date($date)->txncode('to')->orderBy('datetime', $order)->first(array('datetime'));
		} else {
			return false;	
		}
	}
	
	public static function getEmpIns($employeeid=NULL, $date=NULL, $order = 'ASC'){
		if(!is_null($employeeid) || !is_null($date)){
			return self::employeeid($employeeid)->date($date)->txncode('ti')->orderBy('datetime', $order)->get(array('datetime'));
		} else {
			return false;	
		}
	}
	
	public static function getEmpOuts($employeeid=NULL, $date=NULL, $order = 'ASC'){
		if(!is_null($employeeid) || !is_null($date)){
			return self::employeeid($employeeid)->date($date)->txncode('to')->orderBy('datetime', $order)->get(array('datetime'));
		} else {
			return false;	
		}
	}
	
	
	/*********             Query Scopes                           *******************/
	/*********   http://laravel.com/docs/eloquent#query-scopes    *******************/
	public function scopeEmployeeid($query, $employeeid) {
        //return $query->where('employeeid', '=', $employeeid);
		return $query->whereEmployeeid($employeeid);
    }

    public function scopeDate($query, $date) {
        return $query->where('datetime', 'like', $date.'%');
    }
	
	public function scopeTxncode($query, $txncode) {
        //return $query->where('txncode', '=', $txncode);
		return $query->whereTxncode($txncode);
    }
	
	public function scopeEntrytype($query, $entrytype) {
		return $query->whereEntrytype($entrytype);
    }
	
	public function scopeTerminalid($query, $terminalid) { 	
		return $query->whereTerminalid($terminalid);
    }
	/*********             end Query Scopes                           *******************/
	

}