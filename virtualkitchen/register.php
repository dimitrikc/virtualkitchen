<?php 
session_start();
include 'config/config.php';
include 'includes/header.php';

// Registration process
$registration_success = false;
$error_message = "";

if (isset($_POST['register'])) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Check if username already exists
        $check_query = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($check_query);
        
        if ($result->num_rows > 0) {
            $error_message = "Username already exists. Please choose a different one.";
        } else {
            // Check if email already exists
            $check_email = "SELECT * FROM users WHERE email = '$email'";
            $email_result = $conn->query($check_email);
            
            if ($email_result->num_rows > 0) {
                $error_message = "Email already registered. Please use a different email.";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user into database
                $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
                
                if ($conn->query($sql) === TRUE) {
                    $registration_success = true;
                    
                    // Auto-login after registration
                    $user_id = $conn->insert_id;
                    $_SESSION['uid'] = $user_id;
                    $_SESSION['username'] = $username;
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Dimitri's Virtual Kitchen</title>
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

        input[type="text"], input[type="password"], input[type="email"] {
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

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <div class="intro-text">
            <p>Join Dimitri's Virtual Kitchen! Register to share your recipes, discover new dishes, and become part of our cooking community.</p>
        </div>

        <div class="form-container">
            <?php if ($registration_success): ?>
                <div class="success-message">
                    <p>Registration successful! Welcome to Dimitri's Virtual Kitchen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                    <div class="button-container">
                        <a href="recipes.php" class="btn">Browse Recipes</a>
                        <a href="add_recipe.php" class="btn">Add Your First Recipe</a>
                    </div>
                </div>
            <?php else: ?>
                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <label>Username:</label><br>
                    <input type="text" name="username" required><br>

                    <label>Email:</label><br>
                    <input type="email" name="email" required><br>

                    <label>Password:</label><br>
                    <input type="password" name="password" required><br>

                    <label>Confirm Password:</label><br>
                    <input type="password" name="confirm_password" required><br>

                    <div class="button-container">
                        <input type="submit" name="register" value="Register" class="btn">
                    </div>
                </form>

                <div class="button-container">
                    <a href="login.php" class="btn">Already have an account? Login</a>
                </div>
            <?php endif; ?>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Dimitri's Kitchen - Submit your recipes and enjoy!
        </footer>
    </div>
    
</body>
</html>