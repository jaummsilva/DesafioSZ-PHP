<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AdminController extends Action {

	public function loginAdmin() {
		$this->renderDes('loginAdmin');
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