<?php
namespace App\Core;

final class View {
  public static function e(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }

  public static function render(string $view, array $data = []): void {
    extract($data, EXTR_SKIP);
    $viewFile = __DIR__ . "/../Views/{$view}.php";
    if (!file_exists($viewFile)) {
      http_response_code(500);
      echo "View not found.";
      exit;
    }
    require __DIR__ . "/../Views/layout.php";
  }
}
?>