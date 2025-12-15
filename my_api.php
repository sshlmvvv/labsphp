<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Europe/Kiev');

try {
    if (!file_exists('db_connect.php')) {
        throw new Exception("Файл db_connect.php не знайдено!");
    }

    try {
        require_once 'db_connect.php';
    } catch (Exception $e) {
        throw new Exception("Помилка підключення до БД: " . $e->getMessage());
    }

    if (!isset($pdo)) {
        throw new Exception("Змінна \$pdo не ініціалізована в db_connect.php");
    }

    $action = $_GET['action'] ?? '';

    if ($action === 'immediate') {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $serverTime = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO lab5_immediate (event_id, message, client_time, server_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['id'],
                $input['msg'],
                $input['time'],
                $serverTime
            ]);
            echo json_encode(['status' => 'saved']);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Empty input']);
        }
    } 
    elseif ($action === 'batch') {
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input && is_array($input)) {
            $serverTime = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO lab5_batch (event_id, message, client_time, ls_save_time, server_time) VALUES (?, ?, ?, ?, ?)");
            
            try {
                $pdo->beginTransaction();
                foreach ($input as $row) {
                    $stmt->execute([$row['id'], $row['msg'], $row['time'], $row['lsTime'], $serverTime]);
                }
                $pdo->commit();
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                foreach ($input as $row) {
                    $stmt->execute([$row['id'], $row['msg'], $row['time'], $row['lsTime'], $serverTime]);
                }
            }
            echo json_encode(['status' => 'batch_saved']);
        } else {
            echo json_encode(['status' => 'empty']);
        }
    }
    elseif ($action === 'get_results') {
        try {
            $stmt1 = $pdo->query("SELECT * FROM lab5_immediate ORDER BY id ASC");
            $data1 = $stmt1->fetchAll();
        } catch (Exception $e) { $data1 = []; }

        try {
            $stmt2 = $pdo->query("SELECT * FROM lab5_batch ORDER BY id ASC");
            $data2 = $stmt2->fetchAll();
        } catch (Exception $e) { $data2 = []; }

        echo json_encode([
            'immediate' => $data1,
            'batch' => $data2,
            'server_now' => date('Y-m-d H:i:s')
        ]);
    }
    elseif ($action === 'clear') {
        try {
            $pdo->exec("TRUNCATE TABLE lab5_immediate");
            $pdo->exec("TRUNCATE TABLE lab5_batch");
            echo json_encode(['status' => 'cleared']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }
    else {
        echo json_encode(['status' => 'error', 'msg' => 'Unknown action']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>