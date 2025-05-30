<?php
session_start();
include('db_connection.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format. <a href='login.php'>Try again</a>";
            exit();
        }

        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        
        if ($stmt === false) {
            error_log("Failed to prepare statement: " . $conn->error);
            echo "An error occurred. Please try again later.";
            exit();
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = htmlspecialchars($user['username']);

                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password. <a href='login.php'>Try again</a>";
            }
        } else {
            echo "No account found with that email. <a href='login.php'>Try again</a>";
        }

        $stmt->close();
    } else {
        echo "Please provide both email and password. <a href='login.php'>Try again</a>";
    }
}

$conn->close();
?>
