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
            header('Location: /login');
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
        $this->view->errosUsuario = [];
        $this->view->sucessosUsuario = [];
        // Usuario
        $usuario = Container::getModel('Usuario');
        // View que busca o email informado da global POST no banco de dados 
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);
        // Se senha for diferente de repetirSenha retorna com erro
        if(sha1($_POST['senha']) != sha1($_POST['repetirSenha'])) {
            $this->view->errosUsuario[] = "Senha e Repetir Senha devem ser iguais";
            $this->valoresErroUsuario();
            $this->renderAdmin('cadastroUsuarioAdmin');
            return;
        }
        // Se email ja existir , retorna com erro
        if(!empty($this->view->getUsuariosEmail[0])) {
            $this->view->errosUsuario[] = "Email ja existe, tente novamente com outro";
            $this->valoresErroUsuario();
            $this->renderAdmin('cadastroUsuarioAdmin');
            return;
        }
        // Usuario
        $usuario = Container::getModel('Usuario');
        // Setters 
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['data_nascimento']);
        $usuario->__set('telefone',$_POST['telefone']);
        $usuario->__set('imagemId',$_POST['imagemId']);
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
        $this->view->sucessosUsuario = [];
        session_start();
        // Usuario
		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        // View que retorna todos os usuarios
        $this->view->getUsuarios = $usuario->getTodosUsuarios();
        
        $this->renderAdmin('listagemUsuarioAdmin');
    }
    public function editarUsuarioAdmin($id) {
        $this->view->errosUsuario = [];
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
        $this->view->errosUsuario = [];
        $this->view->getUsuario = [];
        $this->view->getUsuariosEmail = $usuario->getTodosUsuariosEmail($_POST['email']);
        // Setters
        $usuario->__set('id',$_POST['id']);
        // View que retorna usuario por id buscado da rota
        $this->view->getUsuario = $usuario->getUsuario();
        // Se senha for diferente de repetirSenha retorna com erro no front
        if(sha1($_POST['senha']) != sha1($_POST['repetirSenha'])) {
            $this->view->errosUsuario[] = "Senha e Repetir Senha devem ser iguais";
            $this->valoresErroUsuario();
            $this->renderAdmin('editarUsuarioAdmin');
            return;
        }
        // Se senha for vazia ou repetirSenha for vazia, retorna com erro no front
        if(($_POST['senha'] == ''  || $_POST['repetirSenha'] == '' )) {
            $this->view->errosUsuario[] = "Insira a senha";
            $this->valoresErroUsuario();
            $this->renderAdmin('editarUsuarioAdmin');
            return;
        }
        // Setters
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
        // Deletação de usuario
        $usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_REQUEST['idUsuario']);
        $usuario->deletarUsuario();
        header("Location: /listagemUsuarioAdmin");
    }
    public function valoresErroUsuario() {
        // Função que coloca todas as informações que o usuario digitou no formulario
        // para se usar caso retorne um erro
        $this->view->getUsuario = [];
        $this->view->getUsuario['id'] =  $_POST['id'];
        $this->view->getUsuario['nome'] =  $_POST['nome'];
        $this->view->getUsuario['data_nascimento'] =  $_POST['data_nascimento'];
        $this->view->getUsuario['telefone'] =  $_POST['telefone'];
        $this->view->getUsuario['senha'] =  $_POST['senha'];
        $this->view->getUsuario['email'] =  $_POST['email'];
        $this->view->getUsuario['imagem_id'] =  $_POST['imagemId'];
    }   

    // Produto

    public function listagemProdutoAdmin() {
		$produto = Container::getModel('Produto');
        // View que retorna todos os produtos
        $this->view->getProdutos = $produto->getTodosProdutos();
        $this->renderAdmin('listagemProdutoAdmin');
    }
    public function editarProdutoAdmin($id) {
        $this->view->errosProduto = [];
        session_start();
        // Produto
		$produto = Container::getModel('Produto');
		$produto->__set('id',$id);
        // View que retorna o produto por id buscado da rota
        $this->view->getProduto = $produto->getProduto();
        $this->renderAdmin('editarProdutoAdmin');
    }
    public function editarProduto() {
        // Views
        $this->view->errosProduto = [];
        $this->view->getProduto = [];
        // Se algum campo do formulario vir com valor vazio, retorna um erro no front
        if($_POST['nome'] == '' || $_POST['preco'] == '' || $_POST['descricao'] == '' || $_POST['imagemId'] == '') {
            $this->view->errosProduto[] = 'Falta completar algum campo';
            $this->valoresErroProduto();
            $this->renderAdmin('editarProdutoAdmin');
            return;
        }
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
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
        $this->view->getProduto = [];
        $this->renderAdmin('cadastroProdutoAdmin');
    }
    public function registrarProduto() {
        $this->view->errosProduto = [];
        // Se algum campo do formulario vir com valor vazio, retorna um erro no front
        if($_POST['nome'] == '' || $_POST['preco'] == '' || $_POST['descricao'] == '' || $_POST['imagemId'] == '') {
            $this->view->errosProduto[] = 'Falta completar algum campo';
            $this->valoresErroProduto();
            $this->renderAdmin('cadastroProdutoAdmin');
            return;
        }
        // Produto
        $produto = Container::getModel('Produto');
        // Setters
        $produto->__set('nome',$_POST['nome']);
		$produto->__set('preco',number_format($_POST['preco'],2, '.', ''));
		$produto->__set('descricao',$_POST['descricao']);
        $produto->__set('imagemId',$_POST['imagemId']);
        // Se o cadastro for validado , registra o produto
        if($produto->validarProduto()) {
            $produto->cadastrarProduto();
            header('Location: /listagemProdutoAdmin');
        }
        else {
            header('Location: /homeAdmin');
        }
    }
    public function deletarProduto() {
        // Deletação do produto
        $produto = Container::getModel('Produto');
		$produto->__set('id',$_REQUEST['idProduto']);
        $produto->deletarProduto();
        header("Location: /listagemProdutoAdmin");
    }
    public function valoresErroProduto() {
        // Função que coloca todas as informações que o usuario digitou no formulario
        // para se usar caso retorne um erro
        $this->view->getProduto = [];
        $this->view->getProduto['id'] =  $_POST['id'];
        $this->view->getProduto['nome'] =  $_POST['nome'];
        $this->view->getProduto['preco'] =  $_POST['preco'];
        $this->view->getProduto['descricao'] =  $_POST['descricao'];
        $this->view->getProduto['imagemId'] =  $_POST['imagemId'];
    }
}



?>