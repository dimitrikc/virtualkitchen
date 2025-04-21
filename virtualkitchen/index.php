
<?php include 'includes/header.php'; ?>
<?php include 'config/config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">


    <title>Dimitri's Virtual Kitchen</title>
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

        .intro-text {
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
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
        }

        .btn:hover {
            background-color: #a68e5a;
        }

        .status-message {
            background-color: #eaf2e0;
            border-left: 5px solid #4a6741;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
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

        <?php if(!empty($connection_status)): ?>
        <div class="status-message">
            <p><?php echo $connection_status; ?></p>
        </div>
        <?php endif; ?>

        <div class="intro-text">
            <p>Welcome to your virtual kitchen, where you can store, organize, and discover your favorite recipes!</p>
        </div>

        <div class="button-container">
            <a href="recipes.php" class="btn">Browse Recipes</a>
            <a href="addrecipe.php" class="btn">Add New Recipe</a>
        </div>

        <footer>
            &copy; <?php echo date("Y"); ?> Dimitri's Kitchen - My First PHP Project, enjoy :)
        </footer>

    </div>

</body>
</html>
