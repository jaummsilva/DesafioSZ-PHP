<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'indexController',
			'action' => 'index'
		);
		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'loginController',
			'action' => 'index'
		);
		$routes['login'] = array(
			'route' => '/cadastro',
			'controller' => 'loginController',
			'action' => 'index'
		);

		$this->setRoutes($routes);
	}

}

?>