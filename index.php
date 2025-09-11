<?php

require_once "Database.php";

$config = require ("config.php");
$db = new Database($config["database"]);

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
                <button class="upcoming">Upcoming</button>
                <button class="today">Today</button>
            </div>
            <div class="lists">
                <button class="all">All</button>
                <button class="personal">Personal</button>
                <button class="work">Work</button>  
                <button class="add-list">+ Add List</button>
            </div>
            <div class="tools">
                <p>Local time</p>  
                <button class="dark-mode">Dark</button>    
            </div>
        </nav>

        <div class="today-tasks">
            <h2>Today</h2>
        </div>
        <div class="upcoming-tasks" style="display: none;">Upcoming</div>
        <div class="personal-tasks" style="display: none;">Personal</div>
        <div class="work-tasks" style="display: none;">Work</div>
        <div class="all-tasks" style="display: none;">All</div>

        <div class="new-task">
            <h2>Add task</h2>
            <form action="add_task.php" method="POST">
                <input type="text" name="title" placeholder="Task..." required>
                <input type="text" name="description" placeholder="Description...">
                <input type="date" name="due_date" required>
                <select name="list">
                    <option value="personal">Personal</option>
                    <option value="work">Work</option>
                </select>
                <button type="submit">Save</button>
            </form>
        </div>
        <div class="edit-task" style="display: none;">Edit Task</div>
    </div>
</body>
</html>