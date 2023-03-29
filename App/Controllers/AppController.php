<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function index() {
		session_start();

		if(!isset($_SESSION['id'])) {
			$_SESSION['id'] = '';
		}
		
		//Models
		$produto = Container::getModel('Produto');
		$carrinho = Container::getModel('Carrinho');
		$usuario = Container::getModel('Usuario');

		// Setters
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$usuario->__set('id',$_SESSION['id']);

		//Views
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutos = $produto->getTodosProdutos();
		
		// Condiçoes
		if(empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = []; 
		}
		if(empty($this->view->getTotalCarrinho)) {
			$this->view->getTotalCarrinho = []; 
		}
		if(empty($this->view->getUsuario)) {
			$this->view->getUsuario = []; 
		}

		// Contador
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;
		
		
		$this->render('index');
	}
	public function produto($id) {
		session_start();
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$produto = Container::getModel('Produto');
		$produto->__set('id',$id);
		$carrinho = Container::getModel('Carrinho');
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		if(empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = []; 
		}
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutoId = $produto->getProduto();
		$this->render('produto');
	}
	public function  inserirProdutoCarrinho() {
		session_start();
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$usuario->getUsuario();
		
		$produtoId = Container::getModel('Produto');
		$produtoId->__set('id',$_REQUEST['idProduto']);
		$produto =  $produtoId->getProduto();
		
		$carrinhoId = Container::getModel('Carrinho');
		$carrinhoId->__set('produtoId', $produto['id']);
		$carrinhoId->__set('usuarioId', $_SESSION['id']);
		$carrinho = $carrinhoId->getCarrinho();
		
		if(empty($carrinho)) {
			$carrinho = Container::getModel('Carrinho');
			$carrinho->__set('preco',$produto['preco']);
			$carrinho->__set('quantidade_Produto',$_REQUEST['qtd_Produto']);
			$carrinho->__set('produtoId', $produto['id']);
			$carrinho->__set('usuarioId', $_SESSION['id']);
			$carrinho->inserirCarrinho();
		}
		else {
			$carrinhoId->__set('quantidade_Produto',$_REQUEST['qtd_Produto']);
			$carrinhoId->updateCarrinho();
		}
	}
	public function removerProdutoCarrinho() {
		$carrinhoId = Container::getModel('Carrinho');
		$carrinhoId->__set('produtoId', $_REQUEST['idProduto']);
		$carrinhoId->__set('usuarioId', $_REQUEST['idUsuario']);
		$carrinhoId->deleteCarrinho();
	}
	public function pesquisarProdutos() {
		session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$this->view->getUsuario = $usuario->getUsuario();

		$carrinho = Container::getModel('Carrinho');
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		if(empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = []; 
		}
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;

		if($_SESSION['id'] != '' && $_SESSION['id'] != '') {
            $pesquisar = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

            $produtos = array();

            if($pesquisar != '') {
                $produto = Container::getModel('Produto');
                $produto->__set('nome',$pesquisar);
                $produtos = $produto->getProdutoPesquisa();
            }
			$this->view->produtos = $produtos;
			$this->render('produtoPesquisado');
        }
	}
	public function criarPedido() {
		session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);

		$carrinho = Container::getModel('Carrinho');
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();

		$pedido = Container::getModel('Pedido');
		$pedido->__set('usuarioId', $_SESSION['id']);
		$pedido->__set('precoTotal', $_REQUEST['preco']);
		$pedido->cadastrarPedido();
		$this->render('pedidoFinalizado');
	}
}
?>