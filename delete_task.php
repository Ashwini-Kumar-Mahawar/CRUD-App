<?php
session_start();
require_once 'config.php';

if (isset($_GET['id'])) {
    $delete_id = intval($_GET['id']);
    $sql = "SELECT id FROM tasks WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $delete_sql = "DELETE FROM tasks WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            $_SESSION['success_message'] = "Task deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting task: ". mysqli_error($conn);
        }

        mysqli_stmt_close($delete_stmt);

    } else {
        $_SESSION['error_message'] = "Task not found or already deleted.";
        header("Location: index.php");
        exit();
    }
    
    mysqli_stmt_close($stmt);
    header("Location: index.php");
    exit();

} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: index.php");
    exit();
}


?>