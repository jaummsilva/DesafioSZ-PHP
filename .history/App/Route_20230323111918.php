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
		$routes['cadastro'] = array(
			'route' => '/cadastro',
			'controller' => 'loginController',
			'action' => 'cadastro'
		);

		$this->setRoutes($routes);
	}

}

?>