<?php
require __DIR__ . '/../app/Core/DB.php';
use App\Core\DB;

try {
    $pdo = DB::pdo();
    
    echo "<style>body { font-family: 'Oswald', sans-serif; margin: 20px; }</style>";
    
    // Verificar a estrutura da tabela
    $result = $pdo->query("PRAGMA table_info(contacts)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Estrutura da tabela 'contacts':</h2>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Nome</th><th>Tipo</th><th>Nulo</th><th>Padrão</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['name']) . "</td>";
        echo "<td>" . htmlspecialchars($column['type']) . "</td>";
        echo "<td>" . ($column['notnull'] ? 'Não' : 'Sim') . "</td>";
        echo "<td>" . ($column['dflt_value'] ?? '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar se user_id existe
    $hasUserIdColumn = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'user_id') {
            $hasUserIdColumn = true;
            break;
        }
    }
    
    echo "<hr>";
    if ($hasUserIdColumn) {
        echo "<p style='color: green;'><strong>✅ Coluna user_id EXISTE na tabela</strong></p>";
    } else {
        echo "<p style='color: orange;'><strong>⚠️ Coluna user_id NÃO EXISTS na tabela</strong></p>";
        echo "<p>A inserção funcionará normalmente sem a coluna user_id. O código tenta atualizar a coluna após inserir (se existir).</p>";
    }
    
    // Testar inserção
    echo "<hr>";
    echo "<h2>Teste de Inserção:</h2>";
    
    require __DIR__ . '/../app/Models/Contact.php';
    use App\Models\Contact;
    
    $id = Contact::create(
        1,
        'Teste User',
        'teste@example.com',
        '0123456789',
        'Hỏi giá',
        'Đây là tin nhắn test'
    );
    
    echo "<p style='color: green;'><strong>✅ Inserção bem-sucedida! ID: $id</strong></p>";
    
    // Verificar primeiro registro
    $stmt = $pdo->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 1");
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($contact) {
        echo "<h3>Último registro inserido:</h3>";
        echo "<pre>";
        print_r($contact);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erro:</h3>";
    echo "<pre style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
?>
