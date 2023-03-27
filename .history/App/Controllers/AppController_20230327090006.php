<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function index() {

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$this->view->getUsuario = $usuario->getUsuario();
		$this->render('index');
	}

}


?>