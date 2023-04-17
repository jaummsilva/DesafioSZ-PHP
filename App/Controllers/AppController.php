<?php

namespace App\Controllers;

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
		$favorito = Container::getModel('Favorito');
		$produto_recomendado = Container::getModel('ProdutoRecomendado');

		// Setters
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$usuario->__set('id', $_SESSION['id']);
		$favorito->__set('usuarioId', $_SESSION['id']);

		//Views
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getProdutos = $produto->getTodosProdutos();
		$this->view->getFavorito = $favorito->getFavoritoUsuario();
		$this->view->getProdutosRecomendados = $produto_recomendado->getTodosProdutosRecomendados();

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
		if (empty($this->view->getFavorito)) {
			$this->view->getFavorito = [];
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
		$favorito = Container::getModel('Favorito');

		// Setters
		$usuario->__set('id', $_SESSION['id']);
		$produto->__set('id', $id);
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('usuarioId', $_SESSION['id']);
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$this->view->getFavorito = $favorito->getFavoritoUsuario();

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
			if ($carrinho->validarCarrinho()) {
				$carrinho->inserirCarrinho();
			}
		}
		// se carrinho existir , update nele 
		else {
			$carrinhoId->__set('quantidade_Produto', $_REQUEST['qtd_Produto']);
			$carrinhoId->__set('data_alteracao', date('Y-m-d H:i:s'));
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
		$carrinhoId->__set('data_alteracao', date('Y-m-d H:i:s'));
		if ($carrinhoId->validarCarrinho()) {
			$carrinhoId->updateQuantidadeCarrinho();
		}
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

		$pesquisar = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';

		$produtos = array();

		if ($pesquisar != '') {
			$produto = Container::getModel('Produto');
			$produto->__set('nome', $pesquisar);
			$produto->__set('descricao', $pesquisar);
			$produtos = $produto->getProdutoPesquisa();
		}
		$this->view->produtos = $produtos;
		$this->render('produtoPesquisado');
	}

	function enviarPedidoSlack($slack_url, $pedidoSlack) {
    // Cria a tabela no formato Markdown
    $table = "*Detalhes do Pedido #" . $pedidoSlack[0]['pedido_id'] . "*\n\n";
    $table .= ":money_with_wings: *Valor total do pedido:* R$" . $pedidoSlack[0]['valor_total_pedido'] . "\n\n";
    $table .= "| *Produto* | *Quantidade* | *Preço* |\n";
    $table .= "| :-- | --: | --: |\n";
    foreach ($pedidoSlack as $linha) {
        $table .= "| " . $linha['nome'] . " | " . $linha['quantidade_produto'] . " | R$" . $linha['preco_total_produto'] . " |\n";
    }
    // Cria a mensagem com a tabela
    $message = "Olá! :wave:\n\n";
    $message .= "Aqui estão os detalhes do seu pedido:\n\n";
    $message .= $table;
    $message .= "\nSe tiver alguma dúvida, fique à vontade para entrar em contato conosco. :blush:";

    $data = array(
        "text" => $message
    );
    $payload = json_encode($data);
    $ch = curl_init($slack_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            "Content-Type: application/json",
            "Content-Length: " . strlen($payload)
        )
    );
    // Armazene a resposta da requisição em uma variável
    $result = curl_exec($ch);
    // Verifique se houve algum erro na requisição
    if (curl_errno($ch)) {
        echo 'Erro na requisição cURL: ' . curl_error($ch);
    }
    // Feche a conexão cURL
    curl_close($ch);
    // Retorne a resposta da requisição
    return $result;
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
		if ($pedido->validarPedido()) {
			$pedido->cadastrarPedido();
		}
		// Id do pedido
		$pedidoId = $pedido->getPedido();
		$this->view->getPedidoId = $pedidoId[0]['id'];
		// Array com o id de todos os produtos do carrinho
		$arrayProduto = $this->view->getCarrinho;
		// Criação de itens do pedido
		foreach ($arrayProduto as $produto) {
			$itenspedido = Container::getModel('ItensPedido');
			$itenspedido->__set('pedidoId', $pedidoId[0]['id']);
			$itenspedido->__set('produtoId', $produto['produto_id']);
			$itenspedido->__set('quantidade_produto', $produto['quantidade_produto']);
			$itenspedido->__set('preco_por_produto', $produto['preco'] * $produto['quantidade_produto']);
			if ($itenspedido->validarItensPedido()) {
				$itenspedido->cadastrarItensPedido();
			}
			$carrinho->__set('produtoId', $produto['produto_id']);
			$carrinho->__set('data_alteracao', date('Y-m-d H:i:s'));
			$carrinho->updateCarrinhoFinalizado();
		}
		/////////////////////// SLACK ////////////////////////
		
		$pedido->__set('id', $pedidoId[0]['id']);
		$pedidoSlack = $pedido->getPedidoSlack();
		$slack_url = "https://hooks.slack.com/services/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";
		$this->enviarPedidoSlack($slack_url,$pedidoSlack);
		///////////////////////////
		$this->renderPedidoFinalizado('pedidoFinalizado');
	}

	// Favorito 
	public function adicionarFavorito()
	{
		session_start();
		$favorito = Container::getModel('Favorito');
		$favorito->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('produtoId', $_POST['produtoId']);
		$favorito->inserirFavorito();
		header('Location: /');
	}
	public function removerFavorito()
	{
		session_start();
		$favorito = Container::getModel('Favorito');
		$favorito->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('produtoId', $_POST['produtoId']);
		$favorito->deleteFavorito();
		header('Location: /');
	}
}
