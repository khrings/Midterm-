<?php
 require_once 'models/authentication.php';
// Store the username for the message
$username = "";
$profile_image = "assets/profile.png"; // Default profile image

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    
    // Use the user's profile image if available
    if (isset($_SESSION["profile_image"]) && !empty($_SESSION["profile_image"])) {
        $profile_image = $_SESSION["profile_image"];
    }
}

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Store the logout status for display
$logout_status = true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - Gluu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00a651; /* Green color from Gluu */
            --text-color: #333;
            --light-color: #f5f5f5;
            --border-color: #ddd;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: white;
            color: var(--text-color);
        }

        .header {
            width: 100%;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            max-width: 150px;
            margin: 0 auto;
        }

        .logout-container {
            text-align: center;
            max-width: 400px;
            width: 90%;
            padding: 2rem;
            border-radius: 8px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logout-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-color);
        }

        .logout-message {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .btn-signin {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-signin:hover {
            background-color: #008c45;
            color: white;
        }

        footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.8rem;
            color: #777;
            width: 100%;
            padding: 1rem;
            border-top: 1px solid var(--border-color);
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="https://placeholder.com/api/placeholder/150/50" alt="Gluu Logo" class="logo" id="logo">
    </div>

    <div class="logout-container">
        <h1 class="logout-title">Logged Out</h1>
        <p class="logout-message">Thank you for using GLUU.</p>
        <a href="signin.php" class="btn-signin">Sign in Again</a>
    </div>

    <footer>
        Gluu, Inc | <a href="https://mit.license/">Use Subject to MIT LICENSE</a>
    </footer>

    <script>
        // Replace placeholder with actual logo
        document.addEventListener('DOMContentLoaded', function() {
            // Set timeout to redirect after 5 seconds
            setTimeout(function() {
                window.location.href = "signin.php";
            }, 5000);
            
            // Replace placeholder with SVG from the page (for demo purposes)
            const logo = document.getElementById('logo');
            logo.src = "data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjUwIiB2aWV3Qm94PSIwIDAgMTUwIDUwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgogIDxwYXRoIGQ9Ik02My4zNSAyNS4zNWMwLTcuNzEgNS42NS0xMy4zNSAxMy4zNS0xMy4zNXMxMy4zNSA1LjY0IDEzLjM1IDEzLjM1YzAgNy43LTUuNjUgMTMuMzQtMTMuMzUgMTMuMzRzLTEzLjM1LTUuNjQtMTMuMzUtMTMuMzR6IiBmaWxsPSIjMDBhNjUxIi8+CiAgPHBhdGggZD0iTTEwNC40NiAyMS4xMmMwLTEuNjMtMS4zMy0yLjk1LTIuOTYtMi45NWgtMjEuODJjLTEuNjMgMC0yLjk2IDEuMzItMi45NiAyLjk1djguNDZjMCAxLjYzIDEuMzMgMi45NSAyLjk2IDIuOTVoMjEuODJjMS42MyAwIDIuOTYtMS4zMiAyLjk2LTIuOTV2LTguNDZ6IiBmaWxsPSIjMDBhNjUxIi8+CiAgPHBhdGggZD0iTTEyMy4wMyAyNy4wN2MwLTEuMDQtMC44NC0xLjg4LTEuODgtMS44OGgtMTAuNDhjLTEuMDQgMC0xLjg4IDAuODUtMS44OCAxLjg4djIuNTJjMCAxLjA0IDAuODQgMS44OCAxLjg4IDEuODhoMTAuNDhjMS4wNCAwIDEuODgtMC44NSAxLjg4LTEuODh2LTIuNTJ6IiBmaWxsPSIjMDBhNjUxIi8+CiAgPHBhdGggZD0iTTEzMi4zNSAzNC4wM2MwLTEuMTgtMC45Ni0yLjE0LTIuMTQtMi4xNGgtMS4xMmMtMS4xOCAwLTIuMTQgMC45Ni0yLjE0IDIuMTR2MS4xMmMwIDEuMTggMC45NiAyLjE0IDIuMTQgMi4xNGgxLjEyYzEuMTggMCAyLjE0LTAuOTYgMi4xNC0yLjE0di0xLjEyeiIgZmlsbD0iIzAwYTY1MSIvPgogIDxwYXRoIGQ9Ik0zNy45OSAyNS4zNWM3LjcxIDAgMTMuMzUgNS42NCAxMy4zNSAxMy4zNXMtNS42NSAxMy4zNC0xMy4zNSAxMy4zNC0xMy4zNS01LjY0LTEzLjM1LTEzLjM0YzAtNy43MSA1LjY1LTEzLjM1IDEzLjM1LTEzLjM1eiIgZmlsbD0iIzAwYTY1MSIvPgogIDxwYXRoIGQ9Ik0yNi4zMyAyNmMxLjYzIDAgMi45NSAxLjMzIDIuOTUgMi45NnYyMS44MmMwIDEuNjMtMS4zMiAyLjk2LTIuOTUgMi45NmgtOC40NmMtMS42MyAwLTIuOTUtMS4zMy0yLjk1LTIuOTZWMjguOTZjMC0xLjYzIDEuMzItMi45NiAyLjk1LTIuOTZoOC40NnoiIGZpbGw9IiMwMGE2NTEiLz4KICA8cGF0aCBkPSJNMjAuNCA3LjQ0YzEuMDQgMCAxLjg4IDAuODQgMS44OCAxLjg4djEwLjQ4YzAgMS4wNC0wLjg0IDEuODgtMS44OCAxLjg4aC0yLjUyYy0xLjA0IDAtMS44OC0wLjg0LTEuODgtMS44OFY5LjMyYzAtMS4wNCAwLjg0LTEuODggMS44OC0xLjg4aDIuNTJ6IiBmaWxsPSIjMDBhNjUxIi8+CiAgPHBhdGggZD0iTTEzLjQzLTEuODZjMS4xOCAwIDIuMTQgMC45NiAyLjE0IDIuMTR2MS4xMmMwIDEuMTgtMC45NiAyLjE0LTIuMTQgMi4xNGgtMS4xMmMtMS4xOCAwLTIuMTQtMC45Ni0yLjE0LTIuMTRWMC4yOGMwLTEuMTggMC45Ni0yLjE0IDIuMTQtMi4xNGgxLjEyeiIgZmlsbD0iIzAwYTY1MSIvPgogIDxwYXRoIGQ9Ik0zOS4yMSAwLjAxYzEwLjkxIDAgMTkuNzUgOC44NCAxOS43NSAxOS43NXMtOC44NCAxOS43NS0xOS43NSAxOS43NS0xOS43NS04Ljg0LTE5Ljc1LTE5Ljc1UzI4LjMgMC4wMSAzOS4yMSAwLjAxem0tMC4wMSAzNC4zM2M4LjA1IDAgMTQuNTktNi41NCAxNC41OS0xNC41OXMtNi41NC0xNC41OS0xNC41OS0xNC41OS0xNC41OSA2LjU0LTE0LjU5IDE0LjU5IDYuNTQgMTQuNTkgMTQuNTkgMTQuNTl6IiBmaWxsPSIjMDBhNjUxIi8+Cjwvc3ZnPgo=";
        });
    </script>
</body>

</html>