<?php 
session_start();
include 'config/config.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Dimitri's Virtual Kitchen</title>
    <style>
        body {
            font-family: 'Playfair Display', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f4e3;
            color: #333333;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #4a6741;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .subtitle {
            font-style: italic;
            margin-top: 8px;
            font-weight: normal;
        }

        nav {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        nav a {
            color: #f8f4e3;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a:hover {
            background-color: #bfa46f;
            color: #fff;
        }

        .intro-text {
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .form-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        label {
            font-weight: bold;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 30px 0;
        }

        .btn {
            font-family: 'Playfair Display', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #bfa46f;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #a68e5a;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <div class="intro-text">
            <p>Welcome back to your Virtual Kitchen! Login to manage your recipes, view your creations, and share your favorite dishes.</p>
        </div>

        <div class="form-container">
            <form method="POST" action="">
                <label>Username:</label><br>
                <input type="text" name="username" required><br>

                <label>Password:</label><br>
                <input type="password" name="password" required><br>

                <div class="button-container">
                    <input type="submit" name="login" value="Login" class="btn">
                </div>
            </form>

            <div class="button-container">
                <a href="register.php" class="btn">No account? Register</a>
            </div>

            <?php
            if (isset($_POST['login'])) {
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = $_POST['password'];

                $sql = "SELECT * FROM users WHERE username = '$username'";
                $result = $conn->query($sql);

                if ($result->num_rows == 1) {
                    $user = $result->fetch_assoc();

                    if (password_verify($password, $user['password'])) {
                        $_SESSION['uid'] = $user['uid'];
                        $_SESSION['username'] = $user['username'];
                        echo "<p>Welcome, " . $user['username'] . "!</p>";
                        echo "<div class='button-container'><a href='recipes.php' class='btn'>Go to Recipes</a></div>";
                    } else {
                        echo "<p>Invalid password.</p>";
                    }
                } else {
                    echo "<p>User not found.</p>";
                }
                
                // Don't close connection here as it might be needed elsewhere
            }
            ?>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Submit your recipes and enjoy!
        </footer>
    </div>
    
</body>
</html>