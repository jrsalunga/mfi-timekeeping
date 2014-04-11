<?php

class BaseController extends Controller {
	
	
	
	public function __construct() {
        // Always run csrf protection before the request when posting
        //$this->beforeFilter('csrf', array('on' => 'post'));

        // Here's something that happens after the request
        //$this->afterFilter(function() {
            // something
        //});
    }

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}