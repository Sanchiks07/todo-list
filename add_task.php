<?php
session_start();

require_once "Database.php";

$config = require ("config.php");
$db = new Database($config["database"]);

$today = date('Y-m-d');
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $list_name = $_POST['list'];

    $list_id_statement = $db->query("SELECT id FROM lists WHERE name = ?", [$list_name]);
    $list_id = $list_id_statement->fetchColumn();

    if ($due_date < $today) {
        $errors[] = "Due date can't be in the past.";
    }

    if (strlen($title) > 50) {
        $errors[] = "Title can't be longer than 50 characters.";
    }

    if (strlen($description) > 150) {
        $errors[] = "Description can't be longer than 150 characters.";
    }

    if (!empty($errors)) {
        $_SESSION['old_inputs'] = [
            'title' => $title,
            'description' => $description,
            'due_date' => $due_date,
            'list' => $list_name
        ];

        $_SESSION['error'] = implode('<br>', $errors);
        header("Location: index.php");
        exit();
    } else {
        // Prepare and execute 
        $sql = "INSERT INTO tasks (task, description, list_id, due_date) VALUES (?, ?, ?, ?)";
        $db->query($sql, [$title, $description, $list_id, $due_date]);

        header("Location: index.php?status=task_added"); // status, ko iegūst js, lai izvadītu pareizo notification
        exit();
    }
}

?>