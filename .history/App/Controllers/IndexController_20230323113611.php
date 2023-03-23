<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class SController extends Action {

	public function index() {

		$this->render('index');
	}

}


?>