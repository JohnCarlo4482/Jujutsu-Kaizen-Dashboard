<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include('db_connection.php');

function createSubjectLinks($baseFolder) {
    $subjects = [
        "BACC100-MICROECONOMICS" => "Microeconomics",
        "BACC102-GOOD GOVERNANCE" => "Good Governance",
        "BACC101-INCOME TAXATION" => "Income Taxation"
    ];

    echo "<ul>";
    foreach ($subjects as $folder => $subjectName) {
        echo "<li><strong>$subjectName</strong>";
        echo "<ul class='content'>";
        $terms = ["Prelim", "Midterm", "Final"];
        foreach ($terms as $term) {
            $termPath = $baseFolder . '/' . $folder . '/' . $term;
            echo "<li><a href='" . htmlspecialchars($termPath) . "' target='_blank'>$term</a></li>";
        }
        echo "</ul></li>";
    }
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* General Styles */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            font-family: Arial, sans-serif;
            color: #F4F4F4;
        }

        /* Background Video */
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        
        /* Overlay for Better Readability */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        /* Centered Dashboard Container */
        .dashboard-container {
            position: relative;
            z-index: 2;
            max-width: 800px;
            width: 90%;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        /* Navigation Styles */
        .nav-links {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 2px solid #F97300;
        }
        .nav-links a {
            text-decoration: none;
            font-weight: bold;
            color: #F97300;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }
        .nav-links a:hover {
            background-color: #F97300;
            color: #343131;
        }

        /* Section Content Styles */
        .section-content {
            padding: 20px;
            background-color: #343131;
            border-radius: 8px;
        }
        h2 {
            font-size: 2rem;
            color: #FFD700;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
        }

        /* List Styles */
        .content {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .content li {
            background-color: #524C42;
            padding: 10px;
            border-radius: 8px;
            width: 100px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .content li:hover {
            background-color: #343131;
        }
        .content a {
            color: #F97300;
            text-decoration: none;
            font-weight: bold;
        }

        /* Welcome Message */
        h1 {
            text-align: center;
            font-size: 2.5rem;
            color: #F4F4F4;
            margin-bottom: 20px;
        }
        span.username {
            color: #FFD700;
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video autoplay muted loop id="bg-video">
        <source src="video.mp4" type="video/mp4">
        Your browser does not support HTML5 video.
    </video>
    
    <!-- Overlay for readability -->
    <div class="overlay"></div>

    <!-- Centered Dashboard Container -->
    <div class="dashboard-container">
        <div class="nav-links">
            <a href="dashboard.php">Homepage</a>
            <a href="dashboard.php?section=modules">Modules</a>
            <a href="dashboard.php?section=activity">Activity</a>
            <a href="dashboard.php?section=quiz">Quiz</a>
            <a href="dashboard.php?section=announcements">Announcements</a>
            <a href="logout.php">Logout</a>
        </div>

        <h1>Welcome, <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?>!</span></h1>
        
        <div class="section-content">
            <?php
            if (isset($_GET['section'])) {
                $section = $_GET['section'];
                switch ($section) {
                    case 'modules':
                        echo "<h2>Modules</h2>";
                        createSubjectLinks("BACC100-MODULE");
                        break;
                    case 'activity':
                        echo "<h2>Activity</h2>";
                        createSubjectLinks("Activity");
                        break;
                    case 'quiz':
                        echo "<h2>Quiz</h2>";
                        createSubjectLinks("Quiz");
                        break;
                    case 'announcements':
                        echo "<h2>Announcements</h2>";
                        echo "<p>No announcements at this time.</p>";
                        break;
                    default:
                        echo "<h2>Homepage</h2>";
                        echo "<p>Welcome to your dashboard. Use the navigation links to explore different sections.</p>";
                }
            } else {
                echo "<h2>Homepage</h2>";
                echo "<p>Welcome to your dashboard. Use the navigation links to explore different sections.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
