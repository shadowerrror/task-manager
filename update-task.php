<?php
require 'config.php';

header('Content-Type: application/json'); // ← JSON ГЛАВА!

if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    !empty($_POST['id']) && 
    in_array($_POST['status'], ['new', 'work', 'done'])) {
    
    $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $success = $stmt->execute([$_POST['status'], (int)$_POST['id']]);
    
    echo json_encode(['success' => true]); // ← JSON!
    exit; // ← СТОП! Ничего больше!
} 

echo json_encode(['success' => false]);
exit;
?>
