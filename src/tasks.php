<?php
session_start();

// タスクがPOSTされた場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // タスクの登録
    if (isset($_POST['task_name']) && isset($_POST['assignee']) && isset($_POST['deadline'])) {
        $newTask = [
            'task_name' => $_POST['task_name'],
            'assignee' => $_POST['assignee'],
            'deadline' => $_POST['deadline'],
            'status' => 'incomplete' // 初期状態は未完了
        ];

        if (!isset($_SESSION['tasks'])) {
            $_SESSION['tasks'] = [];
        }
        $_SESSION['tasks'][] = $newTask;
    }

    // タスクの完了処理
    if (isset($_POST['complete'])) {
        $taskIndex = $_POST['complete'];
        if (isset($_SESSION['tasks'][$taskIndex])) {
            $_SESSION['tasks'][$taskIndex]['status'] = 'complete';
        }
    }
}

// タスクを未完了と完了に分ける
$incompleteTasks = array_filter($_SESSION['tasks'] ?? [], function ($task) {
    return $task['status'] === 'incomplete';
});

$completeTasks = array_filter($_SESSION['tasks'] ?? [], function ($task) {
    return $task['status'] === 'complete';
});
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
</head>
<body>
    <h1>Task Manager</h1>
    <form action="tasks.php" method="post">
        <input type="text" name="task_name" placeholder="Task Name" required>
        <input type="text" name="assignee" placeholder="Assignee" required>
        <input type="date" name="deadline" required>
        <button type="submit">REGISTER</button>
    </form>

    <h2>Incomplete Tasks</h2>
    <ul>
        <?php foreach ($incompleteTasks as $index => $task): ?>
            <li>
                <?= htmlspecialchars($task['task_name']) ?> - <?= htmlspecialchars($task['assignee']) ?> - <?= htmlspecialchars($task['deadline']) ?>
                <form action="tasks.php" method="post" style="display: inline;">
                    <button type="submit" name="complete" value="<?= $index ?>">DONE</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Complete Tasks</h2>
    <ul>
        <?php foreach ($completeTasks as $task): ?>
            <li>
                <?= htmlspecialchars($task['task_name']) ?> - <?= htmlspecialchars($task['assignee']) ?> - <?= htmlspecialchars($task['deadline']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
