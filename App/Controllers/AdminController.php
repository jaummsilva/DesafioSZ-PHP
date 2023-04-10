<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action {
    // Autenticação
	public function loginAdmin() {
        $this->view->errosLogin = [];
		$this->renderDeslogado('loginAdmin');
	}
    public function homeAdmin() {
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        
        $this->renderAdmin('homeAdmin');
    }
    public function autenticarAdmin() {
        $this->view->errosLogin = [];

        // Usuario
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha',sha1($_POST['senha']));
        $usuario->autenticar();
        // Se usuario existir
        if($usuario->__get('id') != '' && $usuario->__get('nome')) {
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /homeAdmin');
        }
        else {
            $this->view->errosLogin[] = "Email ou senha incorretos";
				$this->renderDeslogado('loginAdmin');
        }
    }

    // Usuario 
    public function cadastroUsuarioAdmin() {
        // Views de Erro e Pegar o Usuario
        $this->view->errosUsuario = [];
        $this->view->getUsuario = [];
        session_start();
        // Usuario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        
        $this->renderAdmin('cadastroUsuarioAdmin');
    }
    public function registrarUsuario() {
        // Views de Erro e Pegar o Usuario
        $this->view->getUsuario = [];
        // Usuario
        $usuario = Container::getModel('Usuario');
        // View que busca o email informado da global POST no banco de dados 
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);
        // Se email ja existir , retorna com erro
        if(!empty($this->view->getUsuariosEmail[0])) {
            header('Content-Type: application/json; charset=utf-8'); 
            echo json_encode(['mensagem' => 'Email ja existe, tente novamente com outro']);
            return;
        }
        // Usuario
        $usuario = Container::getModel('Usuario');
        // Setters 
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['nascimento']);
        $usuario->__set('telefone',$_POST['telefone']);
        $usuario->__set('usuario_img',$_POST['img']);
        $usuario->__set('usuario_img_nome',$_POST['imgNome']);
        // Se o cadastro for validado
        if($usuario->validarCadastro()) {
			$usuario->cadastrarUsuario();
            header('Location: /listagemUsuarioAdmin?sucesso=Usuario cadastrado com sucesso');
		}
		else {
			header('Location: /cadastroUsuarioAdmin');
		}
	}
    public function listagemUsuarioAdmin() {
        session_start();
        // Usuario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        // View que retorna todos os usuarios
        $this->view->getUsuarios = $usuario->getTodosUsuarios();
        
        $this->renderAdmin('listagemUsuarioAdmin');
    }
    public function editarUsuarioAdmin($id) {
        session_start();
        // Usuario
        $usuario = Container::getModel('Usuario');
        // Setters
        $usuario->__set('id',$id);
        // View que retorna usuario por id buscado da rota
        $this->view->getUsuario = $usuario->getUsuario();
        $this->renderAdmin('editarUsuarioAdmin');
    }
    public function editarUsuario() {
        $usuario = Container::getModel('Usuario');
        // Views
        $this->view->getUsuario = [];
        // Setters
        $usuario->__set('id',$_POST['id']);
        // View que retorna usuario por id buscado da rota
        $this->view->getUsuario = $usuario->getUsuario();
        // Setters
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['nascimento']);
        $usuario->__set('data_alteracao',date('Y-m-d H:i:s'));
        $usuario->__set('telefone',$_POST['telefone']);
        // se a imagem for alterada
        if(!empty($_POST['img'])) {
            $usuario->__set('usuario_img',$_POST['img']);
            $usuario->__set('usuario_img_nome',$_POST['imgNome']);
        }
        // Se a edição for validada
        if($usuario->validarCadastro()) {
            $usuario->editarUsuario();
            header("Location: /listagemUsuarioAdmin");
        }
        else {
            return;
        }
    }
    public function deletarUsuario() {
        // Deletação de usuario
        $usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_REQUEST['idUsuario']);
        $usuario->deletarUsuario();
        header("Location: /listagemUsuarioAdmin");
    }
    // Produto

    public function listagemProdutoAdmin() {
		$produto = Container::getModel('Produto');
        // View que retorna todos os produtos
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->renderAdmin('listagemProdutoAdmin');
    }
    public function editarProdutoAdmin($id) {
        session_start();
        // Produto
		$produto = Container::getModel('Produto');
		$produto->__set('id',$id);
        // View que retorna o produto por id buscado da rota
        $this->view->getProduto = $produto->getProduto();
        $this->renderAdmin('editarProdutoAdmin');
    }
    public function editarProduto() {
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
        $produto->__set('id',$_POST['id']);
        $produto->__set('nome',$_POST['nome']);
		$produto->__set('preco',number_format($_POST['preco'],2, '.', ''));
		$produto->__set('data_alteracao',date('Y-m-d H:i:s'));
        $produto->__set('descricao',$_POST['descricao']);
        // se a imagem for alterada
        if(!empty($_POST['img'])) {
            $produto->__set('produto_img',$_POST['img']);
            $produto->__set('produto_img_nome',$_POST['imgNome']);
        }
        // se a imagem 2 for alterada 
        if(!empty($_POST['img2'])) {
            $produto->__set('produto_img_2',$_POST['img_2']);
            $produto->__set('produto_img_2_nome',$_POST['imgNome2']);
        }
        // se a imagem 3 for alterada 
        if(!empty($_POST['img3'])) {
            $produto->__set('produto_img_3',$_POST['img3']);
            $produto->__set('produto_img_3_nome',$_POST['imgNome3']);
        }
        // se a imagem 4 for alterada 
        if(!empty($_POST['img4'])) {
            $produto->__set('produto_img_4',$_POST['img4']);
            $produto->__set('produto_img_4_nome',$_POST['imgNome4']);
        }
        // se o produto for validado
        if($produto->validarProduto()) {
            $produto->editarProduto();
            header("Location: /listagemProdutoAdmin");
        }
        else {
            return;
        }
    }
    public function cadastroProdutoAdmin() {
        $this->view->getProduto = [];
        $this->renderAdmin('cadastroProdutoAdmin');
    }
    public function registrarProduto() {
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
        $produto->__set('nome',$_POST['nome']);
		$produto->__set('preco',number_format($_POST['preco'],2, '.', ''));
		$produto->__set('descricao',$_POST['descricao']);
        $produto->__set('produto_img',$_POST['img']);
        $produto->__set('produto_img_nome',$_POST['imgNome']);
        if(!empty($_POST['img2'])) {
            $produto->__set('produto_img_2',$_POST['img2']);
            $produto->__set('produto_img_2_nome',$_POST['imgNome2']);
        }
        if(!empty($_POST['img3'])) {
            $produto->__set('produto_img_3',$_POST['img3']);
            $produto->__set('produto_img_3_nome',$_POST['imgNome3']);
        }
        if(!empty($_POST['img4'])) {
            $produto->__set('produto_img_4',$_POST['img4']);
            $produto->__set('produto_img_4_nome',$_POST['imgNome4']);
        }
        // Se o cadastro for validado , registra o produto
        if($produto->validarProduto()) {
            $produto->cadastrarProduto();
            header('Location: /listagemProdutoAdmin');
        }
    }
    public function deletarProduto() {
        // Deletação do produto
        $produto = Container::getModel('Produto');
		$produto->__set('id',$_REQUEST['idProduto']);
        $produto->deletarProduto();
        header("Location: /listagemProdutoAdmin");
    }
    // Pedido
    public function listagemPedidoAdmin() {
        $pedido = Container::getModel("Pedido");
        $this->view->getPedidos = $pedido->getPedidos();
        $this->renderAdmin('listagemPedidoAdmin');
    }
    public function listagemPedidoProdutoAdmin() {
        $pedido = Container::getModel("Pedido");
        $pedido->__set('id',$_REQUEST['pedidoId']);
        $this->view->getPedidoProduto = $pedido->getPedidoId();
        $this->renderAdmin('listagemPedidoProdutoAdmin');
    }
    // Favorito
    public function listagemFavoritoAdmin() {
        $favorito = Container::getModel("Favorito");
        $this->view->getFavoritos = $favorito->getFavoritos();
        $this->view->getMaisFavoritos = $favorito->getMaisFavoritos();
        $this->renderAdmin('listagemFavoritoAdmin');
    }
}



?>