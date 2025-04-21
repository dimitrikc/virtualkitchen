<?php 
session_start();
include 'config/config.php';

// Make sure user is logged in
if (!isset($_SESSION['uid'])) {
    echo "<p>Please <a href='login.php'>login</a> to delete recipes.</p>";
    exit();
}

// Make sure recipe ID is passed
if (!isset($_GET['rid'])) {
    echo "<p>No recipe selected.</p>";
    exit();
}

$rid = $_GET['rid'];

// Check if the recipe belongs to the logged-in user
$sql = "SELECT * FROM recipes WHERE rid = $rid AND uid = " . $_SESSION['uid'];
$result = $conn->query($sql);

if ($result->num_rows !== 1) {
    echo "<p>Recipe not found or you don't have permission to delete this recipe.</p>";
    exit();
}

// If it belongs to them, delete it
$deleteSql = "DELETE FROM recipes WHERE rid = $rid";

if ($conn->query($deleteSql) === TRUE) {
    echo "<p>Recipe deleted successfully.</p>";
    echo "<a href='recipes.php' class='btn'>Back to Recipes</a>";
} else {
    echo "<p>Error deleting recipe: " . $conn->error . "</p>";
}

$conn->close();
?>
