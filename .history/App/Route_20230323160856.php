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
		$routes['autenticarAdmin'] = array(
			'route' => '/autenticarAdmin',
			'controller' => 'AdminController',
			'action' => 'autenticarAdmin'
		);
		$routes['homeAdmin'] = array(
			'route' => '/homeAdmin',
			'controller' => 'AdminController',
			'action' => 'homeAdmin'
		);
		$routes['cadastroUsuarioAdmin'] = array(
			'route' => '/cadastroUsuarioAdmin',
			'controller' => 'AdminController',
			'action' => 'cadastroUsuarioAdmin'
		);
		$routes['registrar'] = array(
			'route' => '/registrar',
			'controller' => 'AdminController',
			'action' => 'registrar'
		);
		$routes['listagemUsuarioAdmin'] = array(
			'route' => '/listagemUsuarioAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemUsuarioAdmin'
		);
		$routes['editarUsuarioAdmin'] = array(
			'route' => '/editarUsuarioAdmin',
			'controller' => 'AdminController',
			'action' => 'editarUsuarioAdmin'
		);

		$this->setRoutes($routes);
	}

}

?>