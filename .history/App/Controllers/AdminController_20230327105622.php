<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action {


    // Autenticação
	public function loginAdmin() {
		$this->renderDeslogado('loginAdmin');
	}
    public function homeAdmin() {
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        $this->renderAdmin('homeAdmin');
    }
    public function autenticarAdmin() {
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha',sha1($_POST['senha']));
        
        $usuario->autenticar();
        if($usuario->__get('id') != '' && $usuario->__get('nome')) {
            session_start();

            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');

            header('Location: /homeAdmin');
        }
        else {
            header('Location: /login');
        }
    }

    // Usuario 
    public function cadastroUsuarioAdmin() {
        $this->view->errosUsuario = [];
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        $this->renderAdmin('cadastroUsuarioAdmin');
    }
    public function registrarUsuario() {
        $this->view->errosUsuario = [];
        $this->view->sucessosUsuario = [];
        $usuario = Container::getModel('Usuario');
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);

        if(sha1($_POST['senha']) != sha1($_POST['repetirSenha'])) {
            $this->view->errosUsuario[] = "Senha e Repetir Senha devem ser iguais";
            $this->renderAdmin('cadastroUsuarioAdmin');
            return;
        }
        if(!empty($this->view->getUsuariosEmail[0])) {
            $this->view->errosUsuario[] = "Email ja existe, tente novamente com outro";
            $this->renderAdmin('cadastroUsuarioAdmin');
            return;
        }
        
        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['data_nascimento']);
        $usuario->__set('telefone',$_POST['telefone']);
        $usuario->__set('imagemId',$_POST['imagemId']);

        if($usuario->validarCadastro()) {
			$usuario->cadastrarUsuario();
            header('Location: /listagemUsuarioAdmin?sucesso=Usuario cadastrado com sucesso');
		}
		else {
			header('Location: /cadastroUsuarioAdmin');
		}
	}
    public function listagemUsuarioAdmin() {
        $this->view->sucessosUsuario = [];
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        $this->view->getUsuarios = $usuario->getTodosUsuarios();
        
        $this->renderAdmin('listagemUsuarioAdmin');
    }
    public function editarUsuarioAdmin($id) {
        $this->view->errosUsuario = [];
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$id);
        $this->view->getUsuario = $usuario->getUsuario();
        $this->renderAdmin('editarUsuarioAdmin');
    }
    public function editarUsuario() {
        $usuario = Container::getModel('Usuario');
        $this->view->errosUsuario = [];
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);
        $usuario->__set('id',$_POST['id']);
        $this->view->getUsuario = $usuario->getUsuario();
        
        if(sha1($_POST['senha']) != sha1($_POST['repetirSenha'])) {
            $this->view->errosUsuario[] = "Senha e Repetir Senha devem ser iguais";
            $this->renderAdmin('editarUsuarioAdmin');
            return;
        }
        if(($_POST['senha'] == ''  || $_POST['repetirSenha'] == '' )) {
            $this->view->errosUsuario[] = "Insira a senha";
            $this->renderAdmin('editarUsuarioAdmin');
            return;
        }
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['data_nascimento']);
        $usuario->__set('data_alteracao',date('Y-m-d H:i:s'));
        $usuario->__set('telefone',$_POST['telefone']);
        $usuario->__set('imagemId',$_POST['imagemId']);
        $usuario->editarUsuario();
        header("Location: /listagemUsuarioAdmin");
    }
    public function deletarUsuario() {
        $usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_REQUEST['idUsuario']);
        $usuario->deletarUsuario();
        header("Location: /listagemUsuarioAdmin");
    }

    // Produto

    public function listagemProdutoAdmin() {
		$produto = Container::getModel('Produto');
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->renderAdmin('listagemProdutoAdmin');
    }
    public function editarProdutoAdmin($id) {
        $this->view->errosProduto = [];
        session_start();

		$produto = Container::getModel('Produto');
		$produto->__set('id',$id);
        $this->view->getProduto = $produto->getProduto();
        $this->renderAdmin('editarProdutoAdmin');
    }    
    public function editarProduto() {
        $this->view->errosProduto = [];
        $this->view->getProduto = [];
        if($_POST['nome'] == '' || $_POST['preco'] == '' || $_POST['descricao'] == '' || $_POST['imagemId'] == '') {
            $this->view->errosProduto[] = 'Falta completar algum campo';
            $this->view->getProduto['nome'] =  $_POST['nome'];
            $this->view->getProduto['preco'] =  $_POST['descricao'];
            $this->view->getProduto['nome'] =  $_POST['nome'];
            $this->renderAdmin('editarProdutoAdmin');
            return;
        }
        $produto = Container::getModel('Produto');
        $produto->__set('id',$_POST['id']);
        $produto->__set('nome',$_POST['nome']);
		$produto->__set('preco',number_format($_POST['preco'],2, '.', ''));
		$produto->__set('data_alteracao',date('Y-m-d H:i:s'));
        $produto->__set('descricao',$_POST['descricao']);
        $produto->__set('imagemId',$_POST['imagemId']);
        $produto->editarProduto();
        header("Location: /listagemProdutoAdmin");
    }
    public function cadastroProdutoAdmin() {
        $this->view->errosProduto = [];
        $this->renderAdmin('cadastroProdutoAdmin');
    }
    public function registrarProduto() {
        $this->view->errosProduto = [];

        if($_POST['nome'] == '' || $_POST['preco'] == '' || $_POST['descricao'] == '' || $_POST['imagemId'] == '') {
            $this->view->errosProduto[] = 'Falta completar algum campo';
            $this->renderAdmin('cadastroProdutoAdmin');
            return;
        }
        $produto = Container::getModel('Produto');
        $produto->__set('nome',$_POST['nome']);
		$produto->__set('preco',number_format($_POST['preco'],2, '.', ''));
		$produto->__set('descricao',$_POST['descricao']);
        $produto->__set('imagemId',$_POST['imagemId']);
        if($produto->validarProduto()) {
            $produto->cadastrarProduto();
            header('Location: /listagemProdutoAdmin');
        }
        else {
            header('Location: /homeAdmin');
        }
    }
    public function deletarProduto() {
        $produto = Container::getModel('Produto');
		$produto->__set('id',$_REQUEST['idProduto']);
        $produto->deletarProduto();
        header("Location: /listagemProdutoAdmin");
    }
}


?>