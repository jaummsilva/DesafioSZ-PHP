<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action {

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
    public function cadastroUsuarioAdmin() {
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        $this->renderAdmin('cadastroUsuarioAdmin');
    }
    public function registrar() {
        $this->view->errosUsuario = [];
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
			header('Location: /homeAdmin');
		}
		else {
			header('Location: /cadastroUsuarioAdmin');
		}
	}
    public function listagemUsuarioAdmin() {
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
        $this->view->getUsuarios = $usuario->getTodosUsuarios();
        
        $this->renderAdmin('listagemUsuarioAdmin');
    }
    public function editarUsuarioAdmin($id) {
        session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$id);
        $this->view->getUsuario = $usuario->getUsuario();
        $this->renderAdmin('editarUsuarioAdmin');


    }
    public function editar() {
        $this->view->errosUsuario = [];
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
        if(sha1($_POST['senha']) || sha1($_POST['senha']) == '' ) {
            
        }
        session_start();
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_POST['id']);
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
        $usuario->__set('data_nascimento',$_POST['data_nascimento']);
        $usuario->__set('telefone',$_POST['telefone']);
        $usuario->__set('imagemId',$_POST['imagemId']);
        $usuario->editarUsuario();
    }
}


?>