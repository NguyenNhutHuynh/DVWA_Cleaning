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
    'base_url' => 'http://cleaning.test', 'http://localhost/DVWA_Cleaning/public',
    'session_name' => 'CLEANINGSESSID',
  ],
  'payos' => [
    'client_id' => 'aa49a1e1-57f9-4bf7-b3d7-bfa6be17f6b6',
    'api_key' => '987fe9c4-36d1-408a-8996-c292fedd4868',
    'checksum_key' => '2b41b8e02f670cdf6a85702f28219daacb844085c20d4b0d72fd2a35f0862185',
    'webhook_url' => 'https://unthievish-unperpetuated-briella.ngrok-free.dev/webhook.php',
    'bank_account_number' => '0385913461',
    'bank_account_name' => 'NGUYEN ANH KIET',
  ],
];
?>