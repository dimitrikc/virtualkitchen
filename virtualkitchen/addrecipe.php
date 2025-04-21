<?php include 'includes/header.php'; ?>
<?php include 'config/config.php'; ?>
<?php session_start(); ?>

<?php
// Check if form was submitted
if (isset($_POST['submit'])) {
    // Check if user is logged in
    if (!isset($_SESSION['uid'])) {
        $loginError = "<p style='color:red; font-weight:bold;'>You must be logged in to add a recipe.</p>";
    } else {
        // User is logged in, proceed with recipe submission
        // Get form data and sanitize inputs
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $cookingTime = mysqli_real_escape_string($conn, $_POST['Cookingtime']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
        $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
        $uid = $_SESSION['uid'];

        // Handle image upload if provided
        $image_path = "uploads/default.jpg"; // Default image path
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['image']['name'];
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

            // Check if file extension is allowed
            if(in_array(strtolower($file_ext), $allowed)) {
                // Create uploads directory if it doesn't exist
                if (!file_exists('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                $new_name = uniqid() . '.' . $file_ext;
                $upload_path = 'uploads/' . $new_name;

                if(move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $image_path = $upload_path;
                }
            }
        }

        // Use existing connection from config.php
        // No need to create a new connection

        // Insert recipe into database
        $sql = "INSERT INTO recipes (name, description, type, Cookingtime, ingredients, instructions, image, uid)
                VALUES ('$name', '$description', '$type', '$cookingTime', '$ingredients', '$instructions', '$image', '$uid')";

        if (mysqli_query($conn, $sql)) {
            echo "<div style='background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;'>Recipe added successfully!</div>";
            echo "<script>setTimeout(function(){ window.location.href = 'recipes.php'; }, 1500);</script>";
        } else {
            echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <title>Add a New Recipe - Dimitri's Virtual Kitchen</title>
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

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            font-family: 'Playfair Display', Tahoma, Geneva, Verdana, sans-serif;
            background-color:rgb(217, 192, 146);
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #a68e5a;
        }

        .button-link {
            text-decoration: none;
            color: #4a6741;
            font-weight: bold;
        }

        .button-link:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .login-error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #ffeeee;
            border-left: 4px solid red;
            border-radius: 4px;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Add a New Recipe</h2>
    <p>Fill in the details of your new recipe below</p>
    
    <?php if(isset($loginError)): ?>
        <div class="login-error"><?php echo $loginError; ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Recipe Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Description:</label><br>
        <textarea name="description" required></textarea><br>

        <label>Type:</label><br>
        <select name="type" required>
            <option value="Italian">Italian</option>
            <option value="French">French</option>
            <option value="Chinese">Chinese</option>
            <option value="Indian">Indian</option>
            <option value="Mexican">Mexican</option>
            <option value="Carribean">Carribean</option>
            <option value="African">African</option>
            <option value="Others">Others</option>
        </select><br>

        <label>Cooking Time (minutes):</label><br>
        <input type="number" name="Cookingtime" required><br>

        <label>Ingredients:</label><br>
        <textarea name="ingredients" required></textarea><br>

        <label>Instructions:</label><br>
        <textarea name="instructions" required></textarea><br>

        <label>Upload Image:</label><br>
        <input type="file" name="image" accept="image/*"><br>

        <input type="submit" name="submit" value="Add Recipe">
    </form>

    <br>
    <a href="recipes.php" class="button-link">← Back to Recipes</a>

    <footer>
        &copy; <?php echo date("Y"); ?> Dimitri's Kitchen - Submit your recipes and enjoy!
    </footer>
</div>

</body>
</html>