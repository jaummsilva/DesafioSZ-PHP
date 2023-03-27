<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		// Login Controller

		$routes['login'] = array(
			'route' => '/login',
			'controller' => 'loginController',
			'action' => 'index'
		);

		// App Controller

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'appController',
			'action' => 'index'
		);

		// Auth Controller

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

		// Admin Controller
		
		
		$routes['homeAdmin'] = array(
			'route' => '/homeAdmin',
			'controller' => 'AdminController',
			'action' => 'homeAdmin'
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
		$routes['cadastroUsuarioAdmin'] = array(
			'route' => '/cadastroUsuarioAdmin',
			'controller' => 'AdminController',
			'action' => 'cadastroUsuarioAdmin'
		);
		$routes['registrarUsuario'] = array(
			'route' => '/registrarUsuario',
			'controller' => 'AdminController',
			'action' => 'registrarUsuario'
		);
		$routes['listagemUsuarioAdmin'] = array(
			'route' => '/listagemUsuarioAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemUsuarioAdmin'
		);
		$routes['editarUsuarioAdmin'] = array(
			'route' => '/editarUsuarioAdmin/{id}',
			'controller' => 'AdminController',
			'action' => 'editarUsuarioAdmin'
		);
		$routes['editarProdutoAdmin'] = array(
			'route' => '/editarProdutoAdmin/{id}',
			'controller' => 'AdminController',
			'action' => 'editarProdutoAdmin'
		);
		$routes['editarUsuario'] = array(
			'route' => '/editarUsuario',
			'controller' => 'AdminController',
			'action' => 'editarUsuario'
		);
		$routes['editarProduto'] = array(
			'route' => '/editarProduto',
			'controller' => 'AdminController',
			'action' => 'editarProduto'
		);
		$routes['deletarUsuario'] = array(
			'route' => '/deletarUsuario',
			'controller' => 'AdminController',
			'action' => 'deletarUsuario'
		);
		$routes['deletarProduto'] = array(
			'route' => '/deletarProduto',
			'controller' => 'AdminController',
			'action' => 'deletarProduto'
		);
		$routes['cadastroProdutoAdmin'] = array(
			'route' => '/cadastroProdutoAdmin',
			'controller' => 'AdminController',
			'action' => 'cadastroProdutoAdmin'
		);
		$routes['registrarProduto'] = array(
			'route' => '/registrarProduto',
			'controller' => 'AdminController',
			'action' => 'registrarProduto'
		);
		$routes['listagemProdutoAdmin'] = array(
			'route' => '/listagemProdutoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemProdutoAdmin'
		);
		$this->setRoutes($routes);
	}

}

?>