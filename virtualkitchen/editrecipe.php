<?php include 'includes/header.php'; ?>
<?php include 'config/config.php'; ?>

<?php
if (isset($_GET['rid'])) {
    $rid = intval($_GET['rid']);  // sanitize input

    $sql = "SELECT * FROM recipes WHERE rid = $rid";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $recipe = $result->fetch_assoc();
    } else {
        echo "<p>Recipe not found.</p>";
        exit();
    }
} else {
    echo "<p>No recipe selected.</p>";
    exit();
}

// Process form submission for updating the recipe
if (isset($_POST['update_recipe'])) {
    // Sanitize and validate all inputs
    $name = $conn->real_escape_string($_POST['name']);
    $type = $conn->real_escape_string($_POST['type']);
    $cookingTime = intval($_POST['cooking_time']);
    $description = $conn->real_escape_string($_POST['description']);
    $ingredients = $conn->real_escape_string($_POST['ingredients']);
    $instructions = $conn->real_escape_string($_POST['instructions']);
    
    // Handle image upload if provided
    $image = $recipe['image']; // Default to current image
    
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "images/";
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $imageFileType;
        $target_file = $target_dir . $newFileName;
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Check file size and type
            if ($_FILES["image"]["size"] < 5000000 && 
                in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = $newFileName;
                } else {
                    $upload_error = "Sorry, there was an error uploading your file.";
                }
            } else {
                $upload_error = "Sorry, your file is too large or not an accepted image type (JPG, JPEG, PNG, GIF).";
            }
        } else {
            $upload_error = "File is not an image.";
        }
    }
    
    // Update the recipe in the database
    $update_sql = "UPDATE recipes SET 
                    name = '$name', 
                    type = '$type', 
                    Cookingtime = $cookingTime, 
                    description = '$description', 
                    ingredients = '$ingredients', 
                    instructions = '$instructions',
                    image = '$image'
                    WHERE rid = $rid";
    
    if ($conn->query($update_sql) === TRUE) {
        $success_message = "Recipe updated successfully!";
        
        // Refresh recipe data
        $result = $conn->query("SELECT * FROM recipes WHERE rid = $rid");
        $recipe = $result->fetch_assoc();
    } else {
        $error_message = "Error updating recipe: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo htmlspecialchars($recipe['name']); ?> - Dimitri's Virtual Kitchen</title>
    <style>
        body {
            font-family: 'Playfair Display', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f4e3;
            color: #333333;
            line-height: 1.6;
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

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .edit-form {
            background-color: white;
            padding: 25px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .edit-form h2 {
            color: #4a6741;
            margin-top: 0;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #4a6741;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 1rem;
            box-sizing: border-box;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .current-image {
            max-width: 300px;
            max-height: 200px;
            margin-bottom: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .image-preview {
            margin-top: 10px;
        }

        .alert {
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #eaf2e0;
            border-left: 5px solid #4a6741;
            color: #4a6741;
        }

        .alert-danger {
            background-color: #f8e0e0;
            border-left: 5px solid #c74646;
            color: #c74646;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin: 30px 0;
        }

        .btn {
            background-color: #bfa46f;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            text-align: center;
        }

        .btn:hover {
            background-color: #a68e5a;
        }

        .btn-primary {
            background-color: #4a6741;
        }

        .btn-primary:hover {
            background-color: #3a5233;
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
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($upload_error)): ?>
            <div class="alert alert-danger">
                <p><?php echo $upload_error; ?></p>
            </div>
        <?php endif; ?>
        
        <div class="edit-form">
            <h2>Edit Recipe: <?php echo htmlspecialchars($recipe['name']); ?></h2>
            
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Recipe Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($recipe['name']); ?>" required>
                </div>
                
                <div class="form-group">
    <label for="type">Type:</label>
    <select id="type" name="type" class="form-control" required>
        <option value="Italian" <?php echo ($recipe['type'] == 'Italian') ? 'selected' : ''; ?>>Italian</option>
        <option value="French" <?php echo ($recipe['type'] == 'French') ? 'selected' : ''; ?>>French</option>
        <option value="Chinese" <?php echo ($recipe['type'] == 'Chinese') ? 'selected' : ''; ?>>Chinese</option>
        <option value="Indian" <?php echo ($recipe['type'] == 'Indian') ? 'selected' : ''; ?>>Indian</option>
        <option value="Mexican" <?php echo ($recipe['type'] == 'Mexican') ? 'selected' : ''; ?>>Mexican</option>
        <option value="Carribean" <?php echo ($recipe['type'] == 'Carribean') ? 'selected' : ''; ?>>Carribean</option>
        <option value="African" <?php echo ($recipe['type'] == 'African') ? 'selected' : ''; ?>>African</option>
        <option value="Others" <?php echo ($recipe['type'] == 'Others') ? 'selected' : ''; ?>>Others</option>
    </select>
</div>
                
                <div class="form-group">
                    <label for="cooking_time">Cooking Time (minutes)</label>
                    <input type="number" id="cooking_time" name="cooking_time" class="form-control" value="<?php echo intval($recipe['Cookingtime']); ?>" required min="1">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="ingredients">Ingredients (one per line)</label>
                    <textarea id="ingredients" name="ingredients" class="form-control" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="instructions">Instructions</label>
                    <textarea id="instructions" name="instructions" class="form-control" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="image">Recipe Image</label>
                    <?php if (!empty($recipe['image'])): ?>
                        <div class="image-preview">
                            <p>Current image:</p>
                            <img src="images/<?php echo htmlspecialchars($recipe['image']); ?>" class="current-image" alt="Current recipe image">
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <small>Leave empty to keep current image. Only JPG, JPEG, PNG, and GIF files under 5MB are accepted.</small>
                </div>
                
                <div class="button-container">
                    <a href="view_recipe.php?rid=<?php echo $recipe['rid']; ?>" class="btn">Cancel</a>
                    <button type="submit" name="update_recipe" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Submit your recipes and enjoy!
        </footer>
    </div>

    <script>
        // Preview image before upload
        document.getElementById('image').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Check if preview exists, if not create one
                    let preview = document.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('div');
                        preview.className = 'image-preview';
                        e.target.parentNode.appendChild(preview);
                    }
                    
                    // Update preview
                    preview.innerHTML = '<p>New image preview:</p><img src="' + event.target.result + '" class="current-image" alt="Image preview">';
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>