<?php
session_start();
require_once 'config.php';

$edit_mode = false;
$edit_task = null;

if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    
    $sql = "SELECT * FROM tasks WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $edit_task = mysqli_fetch_assoc($result);
        $edit_mode = true;
    } else {
        $_SESSION['error_message'] = "Task not found.";
        header("Location: index.php");
        exit();
    }
    
    mysqli_stmt_close($stmt);

}

if (isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    
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
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error deleting task: " . mysqli_error($conn);
            header("Location: index.php");
            exit();
        }

        mysqli_stmt_close($delete_stmt);

    } else {
        $_SESSION['error_message'] = "Task not found or already deleted.";
        header("Location: index.php");
        exit();
    }

    mysqli_stmt_close($stmt);

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    if (!empty($title)) {
        
        // Check if UPDATE or INSERT
        if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
            
            $edit_id = intval($_POST['edit_id']);
            $status = "pending";
            
            $sql = "UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $status, $edit_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Task updated successfully!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error updating: " . mysqli_error($conn);
                header("Location: index.php");
                exit();
            }
            
            mysqli_stmt_close($stmt);
            
        } else {
            
            $status = "pending";
            
            $sql = "INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $title, $description, $status);
            
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Task added successfully!";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Error: " . mysqli_error($conn);
                header("Location: index.php");
                exit();
            }
            
            mysqli_stmt_close($stmt);
        }
        
    } else {
        $error_message = "Please enter a task title.";
    }
}

$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if ($result == false) {
    die("Error fetching tasks: " . mysqli_error($conn));
}

$task_count = mysqli_num_rows($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
    <h1>CRUD APP</h1>
    <div class="crud_app">
        <h2><?php echo $edit_mode ? 'Edit Task' : 'Add New Task'; ?></h2>
        
        <?php
        if (isset($_SESSION['success_message'])){
            echo ("<div class='message_success'>".$_SESSION['success_message']."</div>");
            unset($_SESSION['success_message']);
        }
        elseif (isset($_SESSION['error_message'])){
            echo ("<div class='message_error'>".$_SESSION['error_message']."</div>");
            unset($_SESSION['error_message']);
        }
        if (isset($error_message)) {
            echo "<div class='message_error'>$error_message</div>";
        }
        ?>
        <form action="" method="post" class="task-form">
            <input type="hidden" name="edit_id" value="<?php echo $edit_mode ? $edit_task['id'] : ''; ?>">
            <div>
                <label for="title">Task Title:</label><br>
                <input type="text" id="title" name="title" placeholder="Enter task title" value="<?php echo $edit_mode ? htmlspecialchars($edit_task['title']) : ''; ?>">
            </div>
            <div>
                <label for="description">Description:</label><br>
                <textarea id="description" name="description" rows="4" placeholder="Enter task description"> <?php echo $edit_mode ? htmlspecialchars($edit_task['description']) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn large-btn"><?php echo $edit_mode ? 'Update Task' : 'Add Task'; ?></button>
            <?php if ($edit_mode) { ?>
                <a href="index.php" class="btn-cancel">Cancel</a>
            <?php } ?>
        </form>

        <h2>Your Tasks</h2>

        <div class="tasks">
            <?php
                $sql = "SELECT * FROM tasks ORDER BY created_at DESC";
                $result = mysqli_query($conn, $sql);
                $task_count = mysqli_num_rows($result);
                if ($result == FALSE){
                    die("Error fetching tasks: . mysqli_error($conn)");
                }
                
                if ($task_count == 0){
                    echo ("<p>No tasks yet. Add one above.</p>");
                }
                else{
            ?>
                    <div class='tasks_container'>
                        <?php
                        while ($task = mysqli_fetch_assoc($result)) {
                        ?>

                        <div class="task_item">
                            <div class="task_header">
                                <h3> <?php echo $task['title']; ?> </h3>
                                <span class="task-status status_<?php echo $task['status']; ?>">
                                    <?php echo $task['status']; ?>
                                </span>
                                <a href="index.php?edit_id=<?php echo $task['id']; ?>" class="btn-edit">Edit</a>
                                <a href="index.php?delete_id=<?php echo $task['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                            </div>
                            <?php 
                                if (!empty($task['description'])){
                                    ?>
                                    <p class='task_description'> <?php echo $task['description']; ?> </p>
                                    <?php
                                } 
                            ?>
                            <p class="task_date">
                                Created: <?php echo date('F j, Y g:i A', strtotime($task['created_at'])); ?>
                            </p>
                        </div>
                        <?php
                        } // Close while loop
                        ?>
                    </div>
                    <?php
                } // Close else block
                ?>
        </div>
    </div>
    <?php mysqli_free_result($result); ?>
    </main>
</body>
</html>