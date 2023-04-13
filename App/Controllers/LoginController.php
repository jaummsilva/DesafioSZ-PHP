<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class LoginController extends Action {

	public function index() {
		$this->view->errosLogin = [];
		$this->renderDeslogado('index');
	}
	public function autenticar() {
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

				header('Location: /');
		}
		//se não existir
		else {
				$this->view->errosLogin[] = "Email ou senha incorretos";
				$this->renderDeslogado('index');
		}
}
public function sair() {
		session_start();
		session_destroy();
		header('Location: /');
}
}
