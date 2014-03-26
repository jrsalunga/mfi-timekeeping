<?php

class DepartmentController extends BaseController {


	public function index() {
		return View::make('department.index');
	}
	
	public function create() {
		return View::make('admin.masterfiles.index');
	}




	


}