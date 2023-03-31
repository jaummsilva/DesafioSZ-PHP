<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

	public function index()
	{
		session_start();

		if (!isset($_SESSION['id'])) {
			$_SESSION['id'] = '';
		}

		//Models
		$produto = Container::getModel('Produto');
		$carrinho = Container::getModel('Carrinho');
		$usuario = Container::getModel('Usuario');

		// Setters
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$usuario->__set('id', $_SESSION['id']);

		//Views
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutos = $produto->getTodosProdutos();

		// Condiçoes
		if (empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = [];
		}
		if (empty($this->view->getTotalCarrinho)) {
			$this->view->getTotalCarrinho = [];
		}
		if (empty($this->view->getUsuario)) {
			$this->view->getUsuario = [];
		}

		// Contador
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;


		$this->render('index');
	}
	public function produto($id)
	{
		session_start();

		// Models
		$carrinho = Container::getModel('Carrinho');
		$produto = Container::getModel('Produto');
		$usuario = Container::getModel('Usuario');

		// Setters
		$usuario->__set('id', $_SESSION['id']);
		$produto->__set('id', $id);
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();

		//Contador
		if (empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = [];
		}
		$contador = count($this->view->getCarrinho);


		//Views
		$this->view->getContador = $contador;
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutoId = $produto->getProduto();
		$this->render('produto');
	}
	public function  inserirProdutoCarrinho()
	{
		session_start();

		// Models
		$usuario = Container::getModel('Usuario');
		$produtoId = Container::getModel('Produto');
		$carrinhoId = Container::getModel('Carrinho');

		$usuario->__set('id', $_SESSION['id']);
		$usuario->getUsuario();

		$produtoId = Container::getModel('Produto');
		$produtoId->__set('id', $_REQUEST['idProduto']);
		$produto =  $produtoId->getProduto();

		$carrinhoId = Container::getModel('Carrinho');
		$carrinhoId->__set('produtoId', $produto['id']);
		$carrinhoId->__set('usuarioId', $_SESSION['id']);
		$carrinho = $carrinhoId->getCarrinho();


		// Se carrinho do usuario não existir - criação do carrinho
		if (empty($carrinho)) {
			$carrinho = Container::getModel('Carrinho');
			$carrinho->__set('preco', $produto['preco']);
			$carrinho->__set('quantidade_Produto', $_REQUEST['qtd_Produto']);
			$carrinho->__set('produtoId', $produto['id']);
			$carrinho->__set('usuarioId', $_SESSION['id']);
			$carrinho->inserirCarrinho();
		}
		// se carrinho existir , update nele 
		else {
			$carrinhoId->__set('quantidade_Produto', $_REQUEST['qtd_Produto']);
			$carrinhoId->updateCarrinho();
		}
	}
	public function  alterarQuantidadeCarrinho()
	{
		session_start();

		// Models
		$usuario = Container::getModel('Usuario');
		$produtoId = Container::getModel('Produto');
		$carrinhoId = Container::getModel('Carrinho');

		$usuario->__set('id', $_SESSION['id']);
		$usuario->getUsuario();

		$produtoId = Container::getModel('Produto');
		$produtoId->__set('id', $_REQUEST['idProduto']);
		$produto =  $produtoId->getProduto();

		$carrinhoId = Container::getModel('Carrinho');
		$carrinhoId->__set('produtoId', $produto['id']);
		$carrinhoId->__set('usuarioId', $_SESSION['id']);
		$carrinhoId->getCarrinho();
		$carrinhoId->__set('quantidade_Produto', $_REQUEST['quantityCarrinho']);
		$carrinhoId->updateQuantidadeCarrinho();
	}
	public function removerProdutoCarrinho()
	{
		// Deletação do produto no carrinho
		$carrinhoId = Container::getModel('Carrinho');
		$carrinhoId->__set('produtoId', $_REQUEST['idProduto']);
		$carrinhoId->__set('usuarioId', $_REQUEST['idUsuario']);
		$carrinhoId->deleteCarrinho();
	}
	public function pesquisarProdutos()
	{
		session_start();

		// Models
		$carrinho = Container::getModel('Carrinho');
		$usuario = Container::getModel('Usuario');

		// Setters
		$usuario->__set('id', $_SESSION['id']);
		$carrinho->__set('usuarioId', $_SESSION['id']);

		// Views
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		if (empty($this->view->getCarrinho)) {
			$this->view->getCarrinho = [];
		}

		// Contador
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;

		if ($_SESSION['id'] != '' && $_SESSION['id'] != '') {
			$pesquisar = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

			$produtos = array();

			if ($pesquisar != '') {
				$produto = Container::getModel('Produto');
				$produto->__set('nome', $pesquisar);
				$produtos = $produto->getProdutoPesquisa();
			}
			$this->view->produtos = $produtos;
			$this->render('produtoPesquisado');
		}
	}
	public function criarPedido()
	{
		session_start();

		// Usuario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);
		//Carrinho
		$carrinho = Container::getModel('Carrinho');
		$carrinho->__set('usuarioId', $_SESSION['id']);
		//Views
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getUsuario = $usuario->getUsuario();
		// Contador
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;
		// Preço total do carrinho
		$precoTotal = $carrinho->getPreçoTotalCarrinho();
		// Criação do pedido
		$pedido = Container::getModel('Pedido');
		$pedido->__set('usuarioId', $_SESSION['id']);
		$pedido->__set('precoTotal', $precoTotal['total_produto']);
		$pedido->cadastrarPedido();
		// Id do pedido
		$pedidoId = $pedido->getPedido();
		// Array com o id de todos os produtos do carrinho
		$arrayProduto = $this->view->getCarrinho;
		// Criação de itens do pedido
		foreach ($arrayProduto as $produto) {
			$itenspedido = Container::getModel('ItensPedido');
			$itenspedido->__set('pedidoId', $pedidoId[0]['id']);
			$itenspedido->__set('produtoId', $produto['produto_id']);
			$itenspedido->__set('quantidade_produto', $produto['quantidade_produto']);
			$itenspedido->__set('preco_por_produto', $produto['preco'] * $produto['quantidade_produto']);
			$itenspedido->cadastrarItensPedido();
			$carrinho->__set('produtoId', $produto['produto_id']);
			$carrinho->updateCarrinhoFinalizado();
		}
		$this->render('pedidoFinalizado');
	}
}
