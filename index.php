<?php
require 'config.php';

// AJAX JSON ответ (оставляем для будущего)
if (isset($_GET['ajax'])) {
    $stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($tasks as &$task) {
        $task['created_at'] = date('d.m H:i', strtotime($task['created_at']));
    }
    header('Content-Type: application/json');
    echo json_encode($tasks);
    exit;
}

$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система задач - Роман Гатин</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📋 Мои задачи</h1>
            <p>Web-разработчик: <strong>Гатин Роман</strong></p>
        </header>

        <div class="add-task">
            <input type="text" id="taskInput" placeholder="Новая задача..." maxlength="100">
            <button onclick="addTask()">Добавить</button>
        </div>

        <div class="tasks-grid" id="tasksGrid">
            <?php foreach($tasks as $task): ?>
            <div class="task-card status-<?= $task['status'] ?>" data-id="<?= $task['id'] ?>">
                <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
                <div class="task-date"><?= date('d.m H:i', strtotime($task['created_at'])) ?></div>
               <div class="status-buttons">
    <button class="status-new" data-tooltip="🆕 Новая задача">🆕</button>
<button class="status-work" data-tooltip="⚡ В работе">⚡</button>
<button class="status-done" data-tooltip="✅ Выполнено">✅</button>

</div>




            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
document.addEventListener('click', function(e) {
    const btn = e.target;
    if (btn.matches('.status-new, .status-work, .status-done')) {
        const card = btn.closest('.task-card');
        const id = card.dataset.id;
        const status = btn.dataset.status || btn.className.split('-')[1];
        
        // МГНОВЕННО меняем цвет
        card.className = `task-card status-${status}`;
        
        // Сохраняем на сервере
        fetch('update-task.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${id}&status=${status}`
        });
    }
});

// data-status для кнопок
document.querySelectorAll('.status-buttons button').forEach(btn => {
    const status = btn.className.split('-')[1];
    btn.dataset.status = status;
});

function addTask() {
    document.getElementById('taskInput').value = '';
    location.reload();
}

document.getElementById('taskInput').addEventListener('keypress', e => {
    if (e.key === 'Enter') addTask();
});
</script>
</body>
</html>
