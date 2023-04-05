<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class LoginController extends Action {

	public function index() {
		$this->renderDeslogado('index');
	}
}


?>