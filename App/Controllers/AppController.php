<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{
	
	public function modalFavorito() {
		session_start();
		$carrinho = Container::getModel('Carrinho');
		$usuario = Container::getModel('Usuario');
		$favorito = Container::getModel('Favorito');
		$usuario->__set('id', $_SESSION['id']);
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('usuarioId', $_SESSION['id']);
		$this->view->getFavorito = $favorito->getFavoritoUsuario();
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->isModalController = true;
		$this->render('modalFavorito');
	}
	public function modalCarrinho() {
		session_start();
		$carrinho = Container::getModel('Carrinho');
		$usuario = Container::getModel('Usuario');
		$favorito = Container::getModel('Favorito');
		
		$usuario->__set('id', $_SESSION['id']);
		$carrinho->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('usuarioId', $_SESSION['id']);
	
		$this->view->getUsuario = $usuario->getUsuario();
		$this->view->getCarrinho = $carrinho->getCarrinhoUsuario();
		$contador = count($this->view->getCarrinho);
		$this->view->getContador = $contador;
		$this->view->getTotalCarrinho = $carrinho->getPreçoTotalCarrinho();
		$this->view->isModalController = true;
		$this->render('modalCarrinho');
	}
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
		$this->view->getFavorito = $favorito->getFavoritoUsuario();
		$this->view->getProdutos = $produto->getTodosProdutos();
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
		$carrinhoId->__set('quantidade_Produto', $_REQUEST['quantityCarrinho']);
		$carrinhoId->__set('data_alteracao', date('Y-m-d H:i:s'));
		if ($carrinhoId->validarCarrinho()) {
			$carrinhoId->updateQuantidadeCarrinho();
			$carrinhoId->getCarrinho();
			$this->view->getTotalCarrinho = $carrinhoId->getPreçoTotalCarrinho();
			header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['valorTotal' => (number_format($this->view->getTotalCarrinho["total_produto"], 2, ',', ''))]);
            return;
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

	function enviarPedidoSlack($slack_url, $pedidoSlack)
	{
		$info = Container::getModel('Info');
		$infos = $info->getInfo();
		// Cria a tabela no formato Markdown
		$table = "*Detalhes do Pedido #" . $pedidoSlack[0]['pedido_id'] . "*\n\n";
		$table .= "*Usuario*: " . $pedidoSlack[0]['usuario_nome'] . " | " . "*Email:* ". $pedidoSlack[0]['email'].  "\n\n";
		$table .= "| *Produto* | *Quantidade* | *Preço* |\n";
		$table .= "| :-- | --: | --: |\n";
		foreach ($pedidoSlack as $linha) {
			$table .= "| " . $linha['nome'] . " | " . $linha['quantidade_produto'] . " | R$" . $linha['preco_total_produto'] . " |\n";
		}
		$table .= "\n";
		$table .= "*Valor total do pedido:* R$" . $pedidoSlack[0]['valor_total_pedido'] . "\n\n";
		// Cria a mensagem com a tabela
		$message = "";
		$message .= $table;

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

		$url = $infos[0]['url'];
		$data = array(
			'number' => '5547984803109',
			'text' => $message
		);

		$data_json = json_encode($data);
		$headers = array(
			'Content-Type : application/json',
			'SecretKey: ' . $infos[0]['secret_key'],
			'PublicToken: ' . $infos[0]['public_key'],
			'DeviceToken: ' . $infos[0]['device_token'],
			'Authorization: ' . $infos[0]['authorization']
		);
		// Inicializa a biblioteca cURL
		$ch = curl_init();

		// Define as opções da requisição
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Executa a requisição
		$response = curl_exec($ch);

		// Fecha a conexão cURL
		curl_close($ch);
		return $result;
	}
	public function criarPedido()
	{
		session_start();

		// Usuario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id', $_SESSION['id']);
		$info = Container::getModel('Info');
		$infos = $info->getInfo();
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
		$slack_url = $infos[0]['slack_url'];
		$this->enviarPedidoSlack($slack_url, $pedidoSlack);
		///////////////////////////
		$this->renderPedidoFinalizado('pedidoFinalizado');
	}

	// Favorito 
	public function adicionarFavorito()
	{
		session_start();
		$favorito = Container::getModel('Favorito');
		$favorito->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('produtoId', $_POST['idProduto']);
		$favorito->inserirFavorito();
		header('Location: /');
	}
	public function removerFavorito()
	{
		session_start();
		$favorito = Container::getModel('Favorito');
		$favorito->__set('usuarioId', $_SESSION['id']);
		$favorito->__set('produtoId', $_POST['idProduto']);
		$favorito->deleteFavorito();
		header('Location: /');
	}
}
