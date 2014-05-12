<?php


class MenuManger {
	
	public function __construct(){
		
	}
	
	private static $_headerMenu = array(
        'navbar-left' => array(
            'masterfiles' => array(
                'caption' => 'Masterfiles',
                'action' => 'index'
            ),
            'transactions' => array(
                'caption' => 'Transactions',
                'action' => 'index'
            ),
            'reports' => array(
                'caption' => 'Reports',
                'action' => 'index'
            ),
        )
    );
	
	public static function getMenu($pos){
		$u = explode('/',Request::url());
		
		$auth = Auth::check();	
        if ($auth) {
           self::$_headerMenu['navbar-right']['dropdown'] = array(
				'settings' => array(
					'caption' => 'Settings',
                	'action' => 'settings',
				),
				'logout' => array(
                	'caption' => 'Log Out',
                	'action' => 'logout'
				)
            );
        } else {
            //unset(self::$_headerMenu['navbar-left']['invoices']);
        }


		if ($auth) {
			echo '<div class="navbar-collapse collapse">';
			//$controllerName = $this->view->getControllerName();
			$controllerName = $u[$pos];
			foreach (self::$_headerMenu as $position => $menu) {
				echo '<ul class="nav navbar-nav ', $position, '">';
				//echo '<ul class="nav navbar-nav">';
				foreach ($menu as $controller => $option) {
					if ($controllerName == $controller) {
						echo '<li class="active">';
						//echo '<li class="'.$option['class'].'">';
					} else {
						echo '<li>';
					} 
					
					
					
					
					if($controller == 'dropdown'){
						//echo $position;
						echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
						echo ' <span class="glyphicon glyphicon-cog"></span> <b class="caret"></b></a>';
						echo '<ul class="dropdown-menu">';
							foreach ($option as $ddk => $ddv) {
								echo '<li><a href="'.$ddv['action'].'">'.$ddv['caption'].'</a></li>';		
							}
						echo '</ul>';
					} else {
						echo HTML::linkRoute('admin.'.$controller.'.index', $option['caption']);
					}
					
					
					
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '</div>';
		}	
    }
	
	
	
	private static $_navs = array(
		'masterfiles' => array(
			'employee' => array(
				'caption' => 'Employee',
                'action' => 'index'
			),
			'department' => array(
				'caption' => 'Department',
                'action' => 'index'
			)
		
		),
		'transactions' => array(
			'timelog' => array(
				'caption' => 'Timelog',
                'action' => 'index'
			)
		),
		'reports' => array(
			'emp-timelog' => array(
				'caption' => 'Employee Timelog',
                'action' => 'emp-timelog'
			),
			'batch-timelog' => array(
				'caption' => 'Batch Timelog',
                'action' => 'batch-timelog'
			)
		),
	);
	
	public static function getNavs(){
		$u = explode('/',Request::url());
		
		$controllerName = $u[4];

        $actionName = isset($u[5]) ? $u[5] : '';

		
		
		
		echo '<ul class="nav nav-pills nav-stacked">';
		foreach (self::$_navs as $main => $sub){
			if ($controllerName == $main) { 
			
				foreach ($sub as $controller => $option) {
					if ($controller == $actionName) {
						echo '<li class="active">';
					} else {
						echo '<li>';
					}
					
					//echo Phalcon\Tag::linkTo($main.'/'.$controller.'/'.$option['action'], $option['caption']);
					echo HTML::linkRoute($controller.'.'.$option['action'], $option['caption']);
					// or
					//echo HTML::link('admin/'.$main.'/'.$controller, $option['caption']);
					echo '</li>';
				}
				echo '</ul>';
			}
		}
		
	}
	
	
	
	
}