<?php
session_start();

$host = 'localhost';
$db = "kaye"; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Updated query to check email instead of username
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password securely
        if (password_verify($password, $hashed_password)) { 
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header("Location: index.php"); 
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not found.";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Kaye's Class</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('background.gif');
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .login-box {
            background: rgba(0, 0, 0, 0.7); 
            padding: 2rem;
            border-radius: 8px;
            width: 300px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 1.5rem;
        }

        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            transition: background 0.3s;
        }

        .login-box button:hover {
            background-color: #0056b3;
        }

        .login-links {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-links a {
            color: white;
            text-decoration: none;
            margin: 10px;
            font-size: 16px;
            padding: 5px 10px;
            border: 2px solid white;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .login-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .error {
            color: #ff6666;
            font-size: 0.9rem;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login to Professor Kaye's Class</h2>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="login-links">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
