<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class LoginController extends Action {

	public function index() {
		$this->renderDeslogado('index');
	}
    public function cadastro() {
		$this->renderDeslogado('cadastro');
	}
    public function registrar() {
        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$_POST['nome']);
		$usuario->__set('email',$_POST['email']);
		$usuario->__set('senha',sha1($_POST['senha']));
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