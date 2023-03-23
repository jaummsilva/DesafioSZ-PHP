<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'appController',
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
		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'loginController',
			'action' => 'registrar'
		);
		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'AuthController',
			'action' => 'autenticar'
		);
		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'AuthController',
			'action' => 'sair'
		);
		$routes['loginAdmin'] = array(
			'route' => '/loginAdmin',
			'controller' => 'AdminController',
			'action' => 'loginAdmin'
		);
		$routes['loginAdmin'] = array(
			'route' => '/loginAdmin',
			'controller' => 'AdminController',
			'action' => 'loginAdmin'
		);

		$this->setRoutes($routes);
	}

}

?>