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
		$this->view->getUsuario = $usuario->getUsuario();
        $this->renderAdmin('homeAdmin');
    }
    public function autenticarAdmin() {
        $usuario = Container::getModel('Usuario');
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha',$_POST['senha']);
        
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
        
    }
    public function registrar() {
        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',$_POST['senha']);
        if($usuario->validarCadastro()) {
			$usuario->cadastrarUsuario();
			header('Location: /login');
		}
		else {
			header('Location: /cadastro');
		}
	}

}


?>