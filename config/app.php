<?php
    return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'cleaning_db',
    'user' => 'cleaning_user',
    'pass' => 'cleaning_pass', 
    'charset' => 'utf8mb4',
  ],
  'app' => [
    'base_url' => 'http://cleaning.test',
    'session_name' => 'CLEANINGSESSID',
  ],
  'payos' => [
    'client_id' => '88c5c667-369b-4187-903f-0aa0d9f34359',
    'api_key' => 'd9aadaf3-4e7d-4987-9c75-5457731d9a64',
    'checksum_key' => '922b18544e809831a8bad56ec78d47e161ae9306a0e8386f7eb494b7d86d8f29',
    'webhook_url' => 'https://suasively-metaphoric-gearldine.ngrok-free.dev/webhook.php',
    'bank_account_number' => '0382583013',
    'bank_account_name' => 'NGUYEN NHUT HUYNH',
  ],
];
?>