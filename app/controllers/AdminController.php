<?php

class AdminController extends BaseController {
	

	public function index() {
		
		return View::make('admin.index');
		/*
		if (Auth::check())	{
			return View::make('admin.index')->with('user', Auth::user());
		} else {
			return Redirect::route('admin.login')->with('login_error', 'You must login first.');
		}	
		*/
	}
	
	public function postLogin(){
		$user = array(
			'username' => Input::get('email'),
			'password' => Input::get('password')
		);
		
		//return var_dump(Auth::attempt($user));
		
			if (Auth::attempt($user))
			{
				//return dd(Input::all());
				return Redirect::route('admin.index');
			}
			return Redirect::route('admin.login')->with('login_error', 'Could not log in.');	
			
	}
	
	public function show() {
		return View::make('admin.index');
	}



	public function login() {
		
		if(Auth::check()){
			return Redirect::route('admin.index');
		} else {
			return View::make('admin.login');
		}		
	}

	public function settings() {
		return 'Settings';
	}

	public function logout() {
		Auth::logout();
		return Redirect::route('admin.login');
	}
	
	public function regUser(){
		$user = new User;
		$user->email = 'jrsalunga@mfi.com';
		$user->password = Hash::make('password');
		$user->name = 'Jefferson Salunga';
		$user->admin = 1;
		$user->id = '9a5f24f2824111e2b7ed5404a67007de';
		
		$user->save();	
	
	}
	
	

	


}