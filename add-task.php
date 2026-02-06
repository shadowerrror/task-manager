<?php
require 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (?)");
    $success = $stmt->execute([$_POST['title']]);
    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Нет заголовка задачи']);
}
?>
