
<header>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <h1>Dimitri's Kitchen</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="recipes.php">Recipes</a>
        <a href="addrecipe.php">Add Recipe</a>
        <?php if (isset($_SESSION['uid'])): ?>
            <a href="logout.php">Logout (<?php echo $_SESSION['username']; ?>)</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>

    <?php if (isset($_SESSION['uid'])): ?>
        <p style="margin: 10px 0 0; font-size: 0.95rem; color: #f8f4e3;">
            Logged in as <strong><?php echo $_SESSION['username']; ?></strong>
        </p>
    <?php endif; ?>
</header>
