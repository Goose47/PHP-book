<?php

namespace Ninja;

class EntryPoint
{
private $route;
private $routes;
private $method;

public function __construct(string $route, \Ninja\Routes $routes, string $method) {
$this -> route = $route;
$this -> routes = $routes;
$this->method = $method;
$this -> checkUrl();
}

private function checkUrl() {
    if(strtolower($this->route) !== $this->route) {
        http_response_code(301);
        header('location: index.php/'.strtolower($this->route));
    }
}

private function loadTemplate($templateFileName, $variables = []) {
    extract($variables);
    ob_start();
    include __DIR__ . '/../../templates/' .$templateFileName;
    return ob_get_clean();
}

public function run()
{
    $routes = $this->routes->getRoutes($this->route);
    $autentication = $this->routes->getAuthentication();

    if (isset($routes[$this->route]['login']) && !$autentication->isLoggedIn()) {
        header('location: index.php?route=login/error');
    } else if (isset($routes[$this->route]['permissions']) && !$this->routes->checkPermission($routes[$this->route]['permissions'])) {
        header('location: index.php?route=permission/error');
    }else{
        $controller = $routes[$this->route][$this->method]['controller'];
        $action = $routes[$this->route][$this->method]['action'];
        //var_dump($this->method);
        $page = $controller->$action();

        $title = $page['title'];

        if(isset($page['variables'])) {
            $output = $this->loadTemplate($page['template'], $page['variables']);
        }else{
            $output = $this->loadTemplate($page['template']);
        }

        echo  $this->loadTemplate('layout.html.php',[
            'loggedIn'=>$autentication->isLoggedIn(),
            'output'=>$output,
            'title'=>$title]);
    }
}
}