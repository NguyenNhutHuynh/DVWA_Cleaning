<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

final class CustomerController {
  public function dashboard(): void {
    if (!Auth::id() || Auth::role() !== 'customer') {
      header("Location: /login");
      exit;
    }

    $me = User::findById((int)Auth::id());
    View::render('customer/dashboard', [
      'uid' => Auth::id(),
      'role' => Auth::role(),
      'name' => $me['name'] ?? 'Khách hàng',
    ]);
  }
}

?>