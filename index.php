<?php
require_once "Database.php";

$config = require("config.php");
$db = new Database($config["database"]);

$tasks = $db->query("SELECT * FROM tasks")->fetchAll();

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
            <div class="top-group">
                <div class="menu" id="menu-toggle" >
                    <img src="icons/menu.png" class="nav_icon">
                </div>
                <div class="tasks">
                    <div class="task_top">
                        <img src="icons/checklist.gif" class="nav_icon">
                        <h2>Tasks</h2>
                    </div>
                    
                    <button class="upcoming" onclick="showTasks('upcoming-tasks', 'today-tasks', 'all-tasks', 'personal-tasks', 'work-tasks')">Upcoming</button>
                    <button class="today" onclick="showTasks('today-tasks', 'upcoming-tasks', 'all-tasks', 'personal-tasks', 'work-tasks')">Today</button>
                </div>
            </div>

            <div class="lists">
                <div class="lists-top">
                    <img src="icons/task.gif" class="nav_icon">
                    <h2>Lists</h2>
                </div>
                
                <button class="all" onclick="showTasks('all-tasks', 'today-tasks', 'upcoming-tasks', 'personal-tasks', 'work-tasks')">All</button>
                <button class="personal" onclick="showTasks('personal-tasks', 'today-tasks', 'all-tasks', 'upcoming-tasks', 'work-tasks')">Personal</button>
                <button class="work" onclick="showTasks('work-tasks', 'today-tasks', 'all-tasks', 'personal-tasks', 'upcoming-tasks')">Work</button>  
                <button class="add-list">+ Add List</button>
            </div>
            <div class="tools">
                <div id="txt"></div>
                <button id="dark-mode" onclick="darkMode()">Dark</button>    
            </div>
        </nav>

        <!-- Today Tasks -->
        <div class="today-tasks" onclick="showDesc(this)">
            <div class="task-icon">
                <img src="icons/checklist.gif" class="nav_icon">
                <h2>Today</h2>
            </div>
            <?php if (!empty($todayTasks)) { ?>
                <?php foreach ($todayTasks as $task) { 
                    $listName = '';
                    foreach ($lists as $list) {
                        if ($list['id'] == $task['list_id']) {
                            $listName = strtolower($list['name']);
                            break;
                        }
                    } ?>

                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $listName ?>">
                        <div class="task-header">
                            <p class="task-title"><?= htmlspecialchars($task["task"]) ?></p>
                            <p class="task-date" data-raw-date="<?= htmlspecialchars($task["due_date"]) ?>">
                                <?= date("d-m-Y", strtotime($task["due_date"])) ?>
                            </p>
                    
                        </div>
                        <div class="task-description" style="display: none;">
                             <p class="description-label">Description:</p>
                            <p><?= htmlspecialchars($task["description"]) ?></p>
                            <button class="edit" onclick="editTask(this)">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No tasks for today</p>
            <?php } ?>
        </div>
        <!-- Upcoming Tasks -->
        <div class="upcoming-tasks" style="display: none;" onclick="showDesc(this)">
            <div class="task-icon">
                <img src="icons/task.gif" class="nav_icon">
                <h2>Upcoming</h2>
            </div>
            <?php if (!empty($upcomingTasks)) { ?>
                <?php foreach ($upcomingTasks as $task) {
                    $listName = '';
                    foreach ($lists as $list) {
                        if ($list['id'] == $task['list_id']) {
                            $listName = strtolower($list['name']);
                            break;
                        }
                    } ?>

                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $listName ?>">
                        <div class="task-header">
                            <p class="task-title"><?= htmlspecialchars($task["task"]) ?></p>
                            <p class="task-date" data-raw-date="<?= htmlspecialchars($task["due_date"]) ?>">
                                <?= date("d-m-Y", strtotime($task["due_date"])) ?>
                            </p>
                            <?php
                                // cik ilgi vel
                                $today = new DateTime('today'); 
                                $dueDate = new DateTime($task["due_date"]);
                                $interval = $today->diff($dueDate);

                                if ($interval->invert) {
                                    // nokavetas dienas
                                    echo '<p class="days-left">Overdue by ' . $interval->days . ' days</p>';
                                } elseif ($interval->days == 0) {
                                    // sodien
                                    echo '<p class="days-left">Due Today!</p>';
                                } else {
                                    // vel ir laiks
                                    echo '<p class="days-left">' . $interval->days . ' days left</p>';
                                }
                            ?>
                        </div>
                        <div class="task-description" style="display: none;">
                             <p class="description-label">Description:</p>
                            <p><?= htmlspecialchars($task["description"]) ?></p>
                            <button class="edit" onclick="editTask(this)">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No upcoming tasks</p>
            <?php } ?>
        </div>
        <!-- Personal Tasks -->
        <div class="personal-tasks" style="display: none;" onclick="showDesc(this)">
            <div class="task-icon">
                <img src="icons/location.gif" class="nav_icon">
                <h2>Personal</h2>
            </div>
            <?php if (!empty($personalTasks)) { ?>
                <?php foreach ($personalTasks as $task) {
                    $listName = '';
                    foreach ($lists as $list) {
                        if ($list['id'] == $task['list_id']) {
                            $listName = strtolower($list['name']);
                            break;
                        }
                    } ?>

                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $listName ?>">
                        <div class="task-header">
                            <p class="task-title"><?= htmlspecialchars($task["task"]) ?></p>
                            <p  class="task-date" data-raw-date="<?= htmlspecialchars($task["due_date"]) ?>">
                                <?= date("d-m-Y", strtotime($task["due_date"])) ?>
                            </p>
                            <?php
                                // cik ilgi vel
                                $today = new DateTime('today'); 
                                $dueDate = new DateTime($task["due_date"]);
                                $interval = $today->diff($dueDate);

                                if ($interval->invert) {
                                    // nokavetas dienas
                                    echo '<p class="days-left">Overdue by ' . $interval->days . ' days</p>';
                                } elseif ($interval->days == 0) {
                                    // sodien
                                    echo '<p class="days-left">Due Today!</p>';
                                } else {
                                    // vel ir laiks
                                    echo '<p class="days-left">' . $interval->days . ' days left</p>';
                                }
                            ?>
                        </div>
                        <div class="task-description" style="display: none;">
                             <p class="description-label">Description:</p>
                            <p><?= htmlspecialchars($task["description"]) ?></p>
                            <button class="edit" onclick="editTask(this)">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No personal tasks</p>
            <?php } ?>
        </div>
        <!-- Work Tasks -->
        <div class="work-tasks" style="display: none;" onclick="showDesc(this)">
            <div class="task-icon">
                <img src="icons/checklist.gif" class="nav_icon">
                <h2>Work</h2>
            </div>
            <?php if (!empty($workTasks)) { ?>
                <?php foreach ($workTasks as $task) { 
                    $listName = '';
                    foreach ($lists as $list) {
                        if ($list['id'] == $task['list_id']) {
                            $listName = strtolower($list['name']);
                            break;
                        }
                    } ?>

                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $listName ?>">
                        <div class="task-header">
                            <p class="task-title"><?= htmlspecialchars($task["task"]) ?></p>
                            <p class="task-date" data-raw-date="<?= htmlspecialchars($task["due_date"]) ?>">
                                <?= date("d-m-Y", strtotime($task["due_date"])) ?>
                            </p>
                            <?php
                                // cik ilgi vel
                                $today = new DateTime('today'); 
                                $dueDate = new DateTime($task["due_date"]);
                                $interval = $today->diff($dueDate);

                                if ($interval->invert) {
                                    // nokavetas dienas
                                    echo '<p class="days-left">Overdue by ' . $interval->days . ' days</p>';
                                } elseif ($interval->days == 0) {
                                    // sodien
                                    echo '<p class="days-left">Due Today!</p>';
                                } else {
                                    // vel ir laiks
                                    echo '<p class="days-left">' . $interval->days . ' days left</p>';
                                }
                            ?>
                        </div>
                        <div class="task-description" style="display: none;">
                            <p class="description-label">Description:</p>
                            <p><?= htmlspecialchars($task["description"]) ?></p>
                            <button class="edit" onclick="editTask(this)">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No work tasks</p>
            <?php } ?>
        </div>
        <!-- All Tasks -->
        <div class="all-tasks" style="display: none;" onclick="showDesc(this)">
            <div class="task-icon">
                <img src="icons/to-do-list.gif" class="nav_icon">
                <h2>All</h2>
            </div>
            <?php if (!empty($tasks)) { ?>
                <?php foreach ($tasks as $task) {
                    $listName = '';
                    foreach ($lists as $list) {
                        if ($list['id'] == $task['list_id']) {
                            $listName = strtolower($list['name']);
                            break;
                        }
                    } ?>

                    <div class="tasks-output" data-id="<?= $task['id'] ?>" data-list="<?= $listName ?>">
                        <div class="task-header">
                            <p class="task-title"><?= htmlspecialchars($task["task"]) ?></p>
                            <p class="task-date" data-raw-date="<?= htmlspecialchars($task["due_date"]) ?>">
                                <?= date("d-m-Y", strtotime($task["due_date"])) ?>
                            </p>
                            <?php
                                // cik ilgi vel
                                $today = new DateTime('today'); 
                                $dueDate = new DateTime($task["due_date"]);
                                $interval = $today->diff($dueDate);

                                if ($interval->invert) {
                                    // nokavetas dienas
                                    echo '<p class="days-left">Overdue by ' . $interval->days . ' days</p>';
                                } elseif ($interval->days == 0) {
                                    // sodien
                                    echo '<p class="days-left">Due Today!</p>';
                                } else {
                                    // vel ir laiks
                                    echo '<p class="days-left">' . $interval->days . ' days left</p>';
                                }
                            ?>
                        </div>
                        <div class="task-description" style="display: none;">
                            <p class="description-label">Description:</p>
                            <p><?= htmlspecialchars($task["description"]) ?><p/>
                            <button class="edit" onclick="editTask(this)">Edit</button>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No tasks added yet</p>
            <?php } ?>
        </div>

        <!-- add task -->
        <div class="new-task">
            <div class="add-top">
                <img src="icons/add-post.png" class="nav_icon">
                <h2>Add task</h2>
            </div>
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
            <div class="task-icon">
                <img src="icons/edit.gif" class="nav_icon">
                <h2>Edit task</h2>
            </div>
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