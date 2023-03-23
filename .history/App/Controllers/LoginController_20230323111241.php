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
        $usuario = Container::getModel('Usuario');
        $usuario->__set('nome',$_POST['nome']);
        $usuario->__set('email',$_POST['email']);
        $usuario->__set('senha',$_POST['nome']);
		$this->renderDeslogado('cadastro');
	}

}


?>