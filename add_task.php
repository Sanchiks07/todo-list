<?php

require_once "Database.php";
$config = require ("config.php");
$db = new Database($config["database"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $list_name = $_POST['list'];

    $list_id_statement = $db->query("SELECT id FROM lists WHERE name = ?", [$list_name]);
    $list_id = $list_id_statement->fetchColumn();

    // Prepare and execute 
    $sql = "INSERT INTO tasks (task, description, list_id, due_date) VALUES (?, ?, ?, ?)";
    $db->query($sql, [$title, $description, $list_id, $due_date]);

    header("Location: index.php");
    exit();
}