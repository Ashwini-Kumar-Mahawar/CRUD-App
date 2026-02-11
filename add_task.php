<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);


    if (!empty($title)) {
        $sql =  "INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        $status = "pending";

        mysqli_stmt_bind_param($stmt, "sss", $title, $description, $status);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Task added successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
        header("Location: index.php");
        exit();
        
    } else {
        $_SESSION['error_message'] = "Please enter a task title.";
        header("Location: index.php");
        exit();
    }

} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: index.php");
    exit();
}
?>