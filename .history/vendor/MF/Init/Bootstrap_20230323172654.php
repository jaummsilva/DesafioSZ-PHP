<?php

namespace MF\Init;

abstract class Bootstrap {
	private $routes;

	abstract protected function initRoutes(); 

	public function __construct() {
		$this->initRoutes();
		$this->run($this->getUrl());
	}

	public function getRoutes() {
		return $this->routes;
	}

	public function setRoutes(array $routes) {
		$this->routes = $routes;
	}

	protected function run($url) {
		foreach ($this->getRoutes() as $key => $route) {
			if($url == $route['route']) {
				$class = "App\\Controllers\\".ucfirst($route['controller']);

				$controller = new $class;
				
				$action = $route['action'];

				$controller->$action();
			} else if(strpos($route['route'],'{id}')) {
				// $url == /editarUsuarioAdmin/adp/1 => ['editarUsuarioAdmin', 1]
				// $route['route'] == /editarUsuarioAdmin/{id} => ['editarUsuarioAdmin/adp', '{id}'] 
				$aUrl = explode('/',$url);
				$aRoute = explode ('/',$route['route']);
				if($a)

			}
		}
	}

	protected function getUrl() {
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	}
}

?>