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
            Navbar
        </nav>

        <div class="today-tasks">Today</div>
        <div class="upcoming-tasks" style="display: none;">Upcoming</div>
        <div class="personal-tasks" style="display: none;">Personal</div>
        <div class="work-tasks" style="display: none;">Work</div>
        <div class="all-tasks" style="display: none;">All</div>

        <div class="new-task">New Task</div>
        <div class="edit-task" style="display: none;">Edit Task</div>
    </div>
</body>
</html>