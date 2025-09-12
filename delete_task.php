<?php

require_once "Database.php";
$config = require ("config.php");
$db = new Database($config["database"]);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $sql = "DELETE FROM tasks WHERE id = ?";
    $db->query($sql, [$id]);

    header("Location: index.php?status=task_deleted"); // status, ko iegūst js, lai izvadītu pareizo notification
    exit();
}

?>