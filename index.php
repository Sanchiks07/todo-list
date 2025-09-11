<?php
require_once "Database.php";

$config = require("config.php");
$db = new Database($config["database"]);

$tasks = $db->query("SELECT * FROM tasks")->fetchAll();
$lists = $db->query("SELECT * FROM lists")->fetchAll();

$taskListName = '';
foreach ($lists as $list) {
    if ($list['id'] == $task['list_id']) {
        $taskListName = strtolower($list['name']);
        break;
    }
}

// iegūst šodienas datumu
$today = date('Y-m-d');

// iegūst list id's priekš personal un work
$lists = $db->query("SELECT * FROM lists")->fetchAll();
$listIds = [];
foreach ($lists as $list) {
    $listIds[strtolower($list['name'])] = $list['id'];
}

// today tasks
$todayTasks = array_filter($tasks, function($task) use ($today) {
    if ($task['due_date'] === $today) {
        return true;
    }
    return false;
});

// upcoming tasks
$upcomingTasks = array_filter($tasks, function($task) use ($today) {
    if ($task['due_date'] > $today) {
        return true;
    }
    return false;
});

// personal tasks
$personalTasks = array_filter($tasks, function($task) use ($listIds) {
    if ($task['list_id'] === $listIds['personal']) {
        return true;
    }
    return false;
});

// work tasks
$workTasks = array_filter($tasks, function($task) use ($listIds) {
    if ($task['list_id'] === $listIds['work']) {
        return true;
    }
    return false;
});

?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <div class="tasks">
                <h2>Tasks</h2>
                <button class="upcoming" onclick="showTasks('upcoming-tasks', 'today-tasks', 'all-tasks', 'personal-tasks', 'work-tasks')">Upcoming</button>
                <button class="today" onclick="showTasks('today-tasks', 'upcoming-tasks', 'all-tasks', 'personal-tasks', 'work-tasks')">Today</button>

            </div>
            <div class="lists">
                <h2>Lists</h2>
                <button class="all" onclick="showTasks('all-tasks', 'today-tasks', 'upcoming-tasks', 'personal-tasks', 'work-tasks')">All</button>
                <button class="personal" onclick="showTasks('personal-tasks', 'today-tasks', 'all-tasks', 'upcoming-tasks', 'work-tasks')">Personal</button>
                <button class="work" onclick="showTasks('work-tasks', 'today-tasks', 'all-tasks', 'personal-tasks', 'upcoming-tasks')">Work</button>  
                <button class="add-list">+ Add List</button>
            </div>
            <div class="tools">
                <div id="txt"></div>
                <button class="dark-mode">Dark</button>    
            </div>
        </nav>

        <div class="today-tasks" onclick="showDesc(this)">
            <h2>Today</h2>
            <?php if (!empty($todayTasks)) { ?>
                <?php foreach ($todayTasks as $task) { ?>
                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $taskListName ?>">
                        <div class="task-header">
                            <p><?= htmlspecialchars($task["task"]) ?></p>
                            <p><?= date("d-m-Y", strtotime($task["due_date"])) ?></p>
                        </div>
                        <div class="task-description" style="display: none;">
                            <br><?= htmlspecialchars($task["description"]) ?>
                            <button class="edit" onclick="editTask(this); event.stopPropagation()">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No tasks for today</p>
            <?php } ?>
        </div>

        <div class="upcoming-tasks" style="display: none;" onclick="showDesc(this)">
            <h2>Upcoming</h2>
            <?php if (!empty($upcomingTasks)) { ?>
                <?php foreach ($upcomingTasks as $task) { ?>
                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $taskListName ?>">
                        <div class="task-header">
                            <p><?= htmlspecialchars($task["task"]) ?></p>
                            <p><?= date("d-m-Y", strtotime($task["due_date"])) ?></p>
                        </div>
                        <div class="task-description" style="display: none;">
                            <br><?= htmlspecialchars($task["description"]) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No upcoming tasks</p>
            <?php } ?>
        </div>

        <div class="personal-tasks" style="display: none;" onclick="showDesc(this)">
            <h2>Personal</h2>
            <?php if (!empty($personalTasks)) { ?>
                <?php foreach ($personalTasks as $task) { ?>
                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $taskListName ?>">
                        <div class="task-header">
                            <p><?= htmlspecialchars($task["task"]) ?></p>
                            <p><?= date("d-m-Y", strtotime($task["due_date"])) ?></p>
                        </div>
                        <div class="task-description" style="display: none;">
                            <br><?= htmlspecialchars($task["description"]) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No personal tasks</p>
            <?php } ?>
        </div>

        <div class="work-tasks" style="display: none;" onclick="showDesc(this)">
            <h2>Work</h2>
            <?php if (!empty($workTasks)) { ?>
                <?php foreach ($workTasks as $task) { ?>
                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $taskListName ?>">
                        <div class="task-header">
                            <p><?= htmlspecialchars($task["task"]) ?></p>
                            <p><?= date("d-m-Y", strtotime($task["due_date"])) ?></p>
                        </div>
                        <div class="task-description" style="display: none;">
                            <br><?= htmlspecialchars($task["description"]) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No work tasks</p>
            <?php } ?>
        </div>

        <div class="all-tasks" style="display: none;" onclick="showDesc(this)">
            <h2>All</h2>
            <?php if (!empty($tasks)) { ?>
                <?php foreach ($tasks as $task) { ?>
                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $taskListName ?>">
                        <div class="task-header">
                            <p><?= htmlspecialchars($task["task"]) ?></p>
                            <p><?= date("d-m-Y", strtotime($task["due_date"])) ?></p>
                        </div>
                        <div class="task-description" style="display: none;">
                            <br><?= htmlspecialchars($task["description"]) ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No tasks added yet</p>
            <?php } ?>
        </div>

        <div class="new-task">
            <h2>Add task</h2>
            <form action="add_task.php" method="POST">
                <input type="text" name="title" placeholder="Task..." required>
                <textarea type="text" name="description" placeholder="Description..."></textarea>
                <input type="date" name="due_date" required>
                <select name="list">
                    <option value="personal">Personal</option>
                    <option value="work">Work</option>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>

        <div class="edit-task" style="display: none;">
            <h2>Edit task</h2>
            <form action="edit_task.php" method="POST">
                <input type="hidden" name="id">
                
                <input type="text" name="title" placeholder="Task..." required>
                <textarea type="text" name="description" placeholder="Description..."></textarea>
                <input type="date" name="due_date" required>
                <select name="list">
                    <option value="personal">Personal</option>
                    <option value="work">Work</option>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>