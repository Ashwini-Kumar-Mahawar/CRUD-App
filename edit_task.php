<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($id > 0 && !empty($title)) {

        $sql = "SELECT id FROM tasks WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $update_sql = "UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            $status = "pending";
            mysqli_stmt_bind_param($update_stmt, "sssi", $title, $description, $status, $id);

            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['success_message'] = "Task updated successfully";
                header("Location: index.php");
                exit(); 
            } else {
                $_SESSION['error_message'] = "Error: ". mysqli_error($conn);
                header("Location: index.php");
                exit();
            }

            mysqli_stmt_close($update_stmt);

        } else {
            $_SESSION['error_message'] = "Task not found.";
            header("Location: index.php");
            exit();
        }

        mysqli_stmt_close($stmt);

    } else {
        $_SESSION['error_message'] = "Invalid data provided.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: index.php");
    exit();
}
?>