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
		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'loginController',
			'action' => 'registrar'
		);

		$this->setRoutes($routes);
	}

}

?>