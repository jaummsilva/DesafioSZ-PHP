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
		$routes['exportarXlsUsuario'] = array(
			'route' => '/exportarXlsUsuario',
			'controller' => 'AdminController',
			'action' => 'exportarXlsUsuario'
		);
		$routes['exportarCsvUsuario'] = array(
			'route' => '/exportarCsvUsuario',
			'controller' => 'AdminController',
			'action' => 'exportarCsvUsuario'
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
		$routes['exportarCsvProduto'] = array(
			'route' => '/exportarCsvProduto',
			'controller' => 'AdminController',
			'action' => 'exportarCsvProduto'
		);
		$routes['exportarXlsProduto'] = array(
			'route' => '/exportarXlsProduto',
			'controller' => 'AdminController',
			'action' => 'exportarXlsProduto'
		);
		$routes['importarProduto'] = array(
			'route' => '/importarProduto',
			'controller' => 'AdminController',
			'action' => 'importarProduto'
		);
		// Admin Controller - Produto Recomendado
		$routes['editarProdutoRecomendadoAdmin'] = array(
			'route' => '/editarProdutoRecomendadoAdmin/{id}',
			'controller' => 'AdminController',
			'action' => 'editarProdutoRecomendadoAdmin'
		);
		$routes['editarProdutoRecomendado'] = array(
			'route' => '/editarProdutoRecomendado',
			'controller' => 'AdminController',
			'action' => 'editarProdutoRecomendado'
		);
		$routes['deletarProdutoRecomendado'] = array(
			'route' => '/deletarProdutoRecomendado',
			'controller' => 'AdminController',
			'action' => 'deletarProdutoRecomendado'
		);
		$routes['cadastroProdutoRecomendadoAdmin'] = array(
			'route' => '/cadastroProdutoRecomendadoAdmin',
			'controller' => 'AdminController',
			'action' => 'cadastroProdutoRecomendadoAdmin'
		);
		$routes['registrarProdutoRecomendado'] = array(
			'route' => '/registrarProdutoRecomendado',
			'controller' => 'AdminController',
			'action' => 'registrarProdutoRecomendado'
		);
		$routes['listagemProdutoRecomendadoAdmin'] = array(
			'route' => '/listagemProdutoRecomendadoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemProdutoRecomendadoAdmin'
		);
		$routes['exportarCsvProdutoRecomendado'] = array(
			'route' => '/exportarCsvProdutoRecomendado',
			'controller' => 'AdminController',
			'action' => 'exportarCsvProdutoRecomendado'
		);
		$routes['exportarXlsProdutoRecomendado'] = array(
			'route' => '/exportarXlsProdutoRecomendado',
			'controller' => 'AdminController',
			'action' => 'exportarXlsProdutoRecomendado'
		);
		// Admin Controller - Pedido
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
		$routes['exportarXlsPedido'] = array(
			'route' => '/exportarXlsPedido',
			'controller' => 'AdminController',
			'action' => 'exportarXlsPedido'
		);
		$routes['exportarCsvPedido'] = array(
			'route' => '/exportarCsvPedido',
			'controller' => 'AdminController',
			'action' => 'exportarCsvPedido'
		);
		// Admin Controller - Favorito
		$routes['listagemFavoritoAdmin'] = array(
			'route' => '/listagemFavoritoAdmin',
			'controller' => 'AdminController',
			'action' => 'listagemFavoritoAdmin'
		);
		$routes['exportarCsvFavorito'] = array(
			'route' => '/exportarCsvFavorito',
			'controller' => 'AdminController',
			'action' => 'exportarCsvFavorito'
		);
		$routes['exportarXlsFavorito'] = array(
			'route' => '/exportarXlsFavorito',
			'controller' => 'AdminController',
			'action' => 'exportarXlsFavorito'
		);
		$this->setRoutes($routes);
	}
}
?>