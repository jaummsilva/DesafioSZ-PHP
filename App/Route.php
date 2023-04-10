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
		$routes['autenticar'] = array(
			'route' => '/autenticar',
			'controller' => 'loginController',
			'action' => 'autenticar'
		);
		$routes['sair'] = array(
			'route' => '/sair',
			'controller' => 'loginController',
			'action' => 'sair'
		);
		// App Controller
		$routes['home'] = array(
			'route' => '/',
			'controller' => 'appController',
			'action' => 'index'
		);
		$routes['produto'] = array(
			'route' => '/produto/{id}',
			'controller' => 'appController',
			'action' => 'produto'
		);
		$routes['alterarQuantidadeCarrinho'] = array(
			'route' => '/alterarQuantidadeCarrinho',
			'controller' => 'appController',
			'action' => 'alterarQuantidadeCarrinho'
		);
		$routes['inserirProdutoCarrinho'] = array(
			'route' => '/inserirProdutoCarrinho',
			'controller' => 'appController',
			'action' => 'inserirProdutoCarrinho'
		);
		$routes['removerProdutoCarrinho'] = array(
			'route' => '/removerProdutoCarrinho',
			'controller' => 'appController',
			'action' => 'removerProdutoCarrinho'
		);
		$routes['pesquisarProdutos'] = array(
			'route' => '/pesquisarProdutos',
			'controller' => 'appController',
			'action' => 'pesquisarProdutos'
		);
		$routes['criarPedido'] = array(
			'route' => '/criarPedido',
			'controller' => 'appController',
			'action' => 'criarPedido'
		);
		$routes['pedidoFinalizado'] = array(
			'route' => '/pedidoFinalizado',
			'controller' => 'appController',
			'action' => 'pedidoFinalizado'
		);
		$routes['adicionarFavorito'] = array(
			'route' => '/adicionarFavorito',
			'controller' => 'appController',
			'action' => 'adicionarFavorito'
		);
		$routes['removerFavorito'] = array(
			'route' => '/removerFavorito',
			'controller' => 'appController',
			'action' => 'removerFavorito'
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

		// Admin Controller - Usuario
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
		$routes['editarUsuario'] = array(
			'route' => '/editarUsuario',
			'controller' => 'AdminController',
			'action' => 'editarUsuario'
		);
		$routes['deletarUsuario'] = array(
			'route' => '/deletarUsuario',
			'controller' => 'AdminController',
			'action' => 'deletarUsuario'
		);
		
		// Admin Controller - Produto
		$routes['editarProdutoAdmin'] = array(
			'route' => '/editarProdutoAdmin/{id}',
			'controller' => 'AdminController',
			'action' => 'editarProdutoAdmin'
		);
		$routes['editarProduto'] = array(
			'route' => '/editarProduto',
			'controller' => 'AdminController',
			'action' => 'editarProduto'
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
		$routes['listagemPedidoAdmin'] = array(
			'route' => '/listagemPedidoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemPedidoAdmin'
		);
		$routes['listagemPedidoProdutoAdmin'] = array(
			'route' => '/listagemPedidoProdutoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemPedidoProdutoAdmin'
		);
		$routes['listagemFavoritoAdmin'] = array(
			'route' => '/listagemFavoritoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemFavoritoAdmin'
		);
		$this->setRoutes($routes);
	}
}
?>