<?php
include 'db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['keys']) && is_array($input['keys'])) {
    $keys = $input['keys'];
    if (empty($keys)) {
        echo json_encode([]);
        exit;
    }
    
    $placeholders = implode(',', array_fill(0, count($keys), '?'));
    
    $sql = "SELECT element_key, content_data FROM content WHERE element_key IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($keys);
    
    $results = [];
    while ($row = $stmt->fetch()) {
        $results[$row['element_key']] = json_decode($row['content_data'], true);
    }
    
    echo json_encode($results);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid keys']);
}
?>