<?php
namespace App\Core;

final class Router {
  private array $get = [];
  private array $post = [];

  public function get(string $path, callable|array $handler): void { $this->get[$path] = $handler; }
  public function post(string $path, callable|array $handler): void { $this->post[$path] = $handler; }

  private function callHandler($handler): void {
    if (is_array($handler)) {
      [$class, $method] = $handler;
      $instance = new $class();
      $instance->$method();
    } else {
      $handler();
    }
  }

  public function dispatch(): void {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    $map = ($method === 'POST') ? $this->post : $this->get;
    if (!isset($map[$uri])) {
      http_response_code(404);
      echo "404 Not Found";
      return;
    }
    $this->callHandler($map[$uri]);
  }
}
?>