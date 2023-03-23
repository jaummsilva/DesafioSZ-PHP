<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function index() {
		session_start();

		$usuario = Container::getModel('Usuario');
		$usuario->__set('id',$_SESSION['id']);
		$this->view->getUser = $usuario->getUser();


		$this->render('index');
	}

}


?>