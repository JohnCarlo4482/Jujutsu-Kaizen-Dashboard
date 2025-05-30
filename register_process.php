<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kaye";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $school_id = sanitize_input($_POST['school_id']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

  
    if (empty($username) || empty($email) || empty($school_id) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }


    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

  
    $checkQuery = "SELECT * FROM users WHERE email = ? OR username = ? OR school_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ssi", $email, $username, $school_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Email, username, or school ID already exists.");
    } else {
      
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insertQuery = "INSERT INTO users (username, email, school_id, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssis", $username, $email, $school_id, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.php'>Login here</a>";
        } else {
           
            error_log("Database error: " . $stmt->error);
            echo "Something went wrong. Please try again later.";
        }
    }

  
    $stmt->close();
}

$conn->close();
?>
