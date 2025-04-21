<?php include 'includes/header.php'; ?>
<?php include 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe List - Dimitri's Virtual Kitchen</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
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
        
        .status-message {
            background-color: #eaf2e0;
            border-left: 5px solid #4a6741;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .button-container {
            display: flex;
            justify-content: center;
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
            display: inline-block;
        }
        
        .btn:hover {
            background-color: #a68e5a;
        }
        
        .intro-text {
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        footer {
            text-align: center;
            margin-top: 50px;
            color: #666;
            font-size: 0.9rem;
        }

        /* Recipe grid styles */
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        
        .recipe-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        
        .recipe-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
        }
        
        .recipe-info {
            padding: 15px;
        }
        
        .recipe-info h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #4a6741;
        }
        
        .recipe-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .view-recipe {
            display: block;
            text-align: center;
            background-color: #bfa46f;
            color: white;
            padding: 8px 0;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .view-recipe:hover {
            background-color: #a68e5a;
        }
        
        .no-recipes {
            text-align: center;
            padding: 40px 0;
            color: #666;
        }
        
        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .back-home {
            color: #4a6741;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="navigation">
            <a href="index.php" class="back-home">← Back to Home</a>
            <h2>All Recipes</h2>
            <a href="addrecipe.php" class="btn">Add New Recipe</a>

        </div>

        <div class="recipe-grid">
        <?php
        $sql = "SELECT * FROM recipes";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='recipe-card'>";
                
                // Recipe image
                if (isset($row['image']) && !empty($row['image'])) {
                    echo "<img src='images/" . htmlspecialchars($row['image']) . "' class='recipe-img' alt='Recipe Image'>";
                } else {
                    echo "<img src='images/default.jpg' class='recipe-img' alt='Default Recipe Image'>";
                }
                
                echo "<div class='recipe-info'>";
                // Recipe name
                echo "<h3>" . (isset($row['name']) ? htmlspecialchars($row['name']) : 'Unknown Recipe') . "</h3>";
                
                // Recipe type
                echo "<p><strong>Type:</strong> " . (isset($row['type']) ? htmlspecialchars($row['type']) : 'Unknown Type') . "</p>";
                
                // Cooking time
                echo "<p><strong>Cooking Time:</strong> " . (isset($row['Cookingtime']) ? htmlspecialchars($row['Cookingtime']) . " mins" : 'Unknown') . "</p>";
                echo "</div>";
                
                // View recipe link
                echo "<a href='recipeview.php?rid=" . $row['rid'] . "' class='view-recipe'>View Recipe</a>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-recipes'>";
            echo "<p>No recipes found. Add your first recipe to get started!</p>";
            echo "</div>";
        }

        $conn->close();
        ?>
        </div>
        
        <footer>
            &copy; <?php echo date("Y"); ?> Dimitri's Kitchen - Submit your recipes and enjoy!
        </footer>
    </div>
</body>
</html>