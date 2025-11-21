<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db_connect.php';

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!isset($data['items']) || !is_array($data['items'])) {
    echo json_encode([
        'success' => false,
        'error'   => 'Некоректні дані (items не передано)'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$items = $data['items'];

if (empty($items)) {
    echo json_encode([
        'success' => false,
        'error'   => 'Порожній набір елементів'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo->exec("TRUNCATE TABLE collapses");

    $stmt = $pdo->prepare(
        "INSERT INTO collapses (title, content, position)
         VALUES (:title, :content, :position)"
    );

    foreach ($items as $item) {
        $stmt->execute([
            ':title'    => $item['title'],
            ':content'  => $item['content'],
            ':position' => $item['position'],
        ]);
    }

    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error'   => 'Помилка БД: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
