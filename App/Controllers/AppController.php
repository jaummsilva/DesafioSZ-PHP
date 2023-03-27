<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function index() {
		session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$produto = Container::getModel('Produto');
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutos = $produto->getTodosProdutos();
		$this->render('index');
	}
	public function produto($id) {
		session_start();
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$produto = Container::getModel('Produto');
		$produto->__set('id',$id);
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutoId = $produto->getProduto();
		$this->render('produto');
	}

	public function  inserirProdutoCarrinho() {
		session_start();
		$produtoId = Container::getModel('Produto');
		$produtoId->__set('id',$_REQUEST['idProduto']);
		$produto =  $produtoId->getProduto();
		$carrinho = Container::getModel('Carrinho');
		$carrinho->__set('preco',$produto['preco']);
		$carrinho->__set('quantidade_Produto',$_REQUEST['quantidade_Produto']);
		$carrinho->__set('produtoId',$produto['id']);
		$carrinho->__set('usuarioId',$_SESSION['id']);
		header('Location: /');
	}
}
?>