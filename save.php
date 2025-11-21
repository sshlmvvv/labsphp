<?php
include 'db_connect.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['key']) && isset($input['data'])) {
    $key = $input['key'];
    $data = $input['data'];
    
    $sql = "INSERT INTO content (element_key, content_data, content_type) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            content_data = VALUES(content_data), 
            content_type = VALUES(content_type)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $key, 
        json_encode($data),
        $data['type']
    ]);
    
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>