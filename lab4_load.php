<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db_connect.php';

try {
    $stmt = $pdo->query(
        "SELECT id, title, content, position
         FROM collapses
         ORDER BY position ASC, id ASC"
    );

    $items = [];

    while ($row = $stmt->fetch()) {
        $items[] = [
            'id'       => (int)$row['id'],
            'title'    => $row['title'],
            'content'  => $row['content'],
            'position' => (int)$row['position']
        ];
    }

    echo json_encode([
        'success' => true,
        'items'   => $items
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error'   => 'Помилка БД: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
