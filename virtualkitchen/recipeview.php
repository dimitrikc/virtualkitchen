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

// Process delete request
if (isset($_POST['delete_recipe'])) {
    $delete_id = intval($_POST['recipe_id']);
    
    // Delete the recipe from the database
    $delete_sql = "DELETE FROM recipes WHERE rid = $delete_id";
    
    if ($conn->query($delete_sql) === TRUE) {
        // Redirect to recipes page after successful deletion
        header("Location: recipes.php?status=deleted");
        exit();
    } else {
        $error_message = "Error deleting recipe: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['name']); ?> - Dimitri's Virtual Kitchen</title>
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

        .recipe-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .recipe-info {
            background-color: white;
            padding: 25px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .recipe-info h2 {
            color: #4a6741;
            margin-top: 0;
            font-size: 1.8rem;
        }

        .recipe-info h3 {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #bfa46f;
            border-bottom: 2px solid #f8f4e3;
            padding-bottom: 5px;
        }

        .recipe-info p {
            margin: 10px 0;
            color: #555;
        }

        .recipe-meta {
            background-color: #eaf2e0;
            border-left: 5px solid #4a6741;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
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

        .btn-delete {
            font-family:'Playfair Display', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #c74646;
        }

        .btn-delete:hover {
            background-color: #a83b3b;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #f8f4e3;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
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
        <?php if (isset($error_message)): ?>
            <div class="recipe-meta" style="border-left-color: #c74646; background-color: #f8e0e0;">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <div class="recipe-info">
            <h2><?php echo htmlspecialchars($recipe['name']); ?></h2>
            
            <img src="images/<?php echo (!empty($recipe['image']) ? htmlspecialchars($recipe['image']) : 'default.jpg'); ?>" 
                 class="recipe-img" alt="<?php echo htmlspecialchars($recipe['name']); ?>">

            <div class="recipe-meta">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($recipe['type']); ?></p>
                <p><strong>Cooking Time:</strong> <?php echo htmlspecialchars($recipe['Cookingtime']); ?> mins</p>
            </div>
            
            <p><strong>Description:</strong> <?php echo htmlspecialchars($recipe['description']); ?></p>
            
            <h3>Ingredients</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>

            <h3>Instructions</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
        </div>

        <div class="button-container">
            <a href="recipes.php" class="btn">← Back to Recipes</a>
            <a href="editrecipe.php?rid=<?php echo $recipe['rid']; ?>" class="btn">Edit Recipe</a>
            <button onclick="document.getElementById('deleteModal').style.display='block'" class="btn btn-delete">Delete Recipe</button>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h3>Confirm Deletion</h3>
                <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($recipe['name']); ?></strong>? This action cannot be undone.</p>
                
                <form method="POST" action="">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe['rid']; ?>">
                    <div class="modal-buttons">
                        <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" class="btn">Cancel</button>
                        <button type="submit" name="delete_recipe" class="btn btn-delete">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Submit your recipes and enjoy!
        </footer>
    </div>

    <script>
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>