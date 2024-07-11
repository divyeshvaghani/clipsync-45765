<?php
// Define the simple password
define('SIMPLE_PASSWORD', 'edit');

// Start the session
session_start();

// Handle data saving
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password'])) {
        if ($_POST['password'] === SIMPLE_PASSWORD) {
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
        } else {
            $message = '<p style="color: red;">Incorrect password. Please try again.</p>';
        }
    } elseif (isset($_POST['user_data'])) {
        file_put_contents('/var/www/html/data.txt', $_POST['user_data']);
        $message = '<p id="save-message" style="color: green;">Data saved successfully!</p>';
    }
}

// Sanitize user input for display
function sanitize_text_field($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Check if session has expired
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 1800)) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['login_time']);
}

// Display the form
if (!isset($_SESSION['logged_in'])) {
    echo '<h1>Enter Password</h1>
        <form method="post">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>';
} else {
    $user_data = file_exists('/var/www/html/data.txt') ? file_get_contents('/var/www/html/data.txt') : '';
    echo '<h1>Welcome!</h1>' . $message . '
        <form method="post">
            <label for="user_data">Enter your data:</label><br>
            <textarea id="user_data" name="user_data" rows="4" style="width: 75%; max-width: 100%;">' . sanitize_text_field($user_data) . '</textarea><br>
            <button type="submit" name="save_data">Save</button>
            <button type="button" onclick="location.reload();" style="margin-left: 10px;">Refresh</button>
        </form>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const message = document.getElementById("save-message");
                if (message) {
                    setTimeout(function() {
                        message.style.display = "none";
                    }, 1000); // 1 second
                }
            });
        </script>';
}
