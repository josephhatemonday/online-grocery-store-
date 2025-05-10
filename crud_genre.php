<?php
// Database connection
include_once("connection.php");

// CREATE operation: Add a new genre
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $sql = "INSERT INTO Genres (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo "New genre added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// READ operation: Show all genres
$sql = "SELECT * FROM Genres";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genres CRUD Application</title>
</head>
<body>

<h1>Genres CRUD Application</h1>

<!-- Form to add a new genre -->
<h2>Add a Genre</h2>
<form method="POST">
    <label for="name">Genre Name:</label>
    <input type="text" id="name" name="name" required>
    <button type="submit" name="create">Add Genre</button>
</form>

<!-- Display all genres -->
<h2>All Genres</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['name'] . "</td><td>";
            echo "<a href='?edit=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='?delete=" . $row['id'] . "'>Delete</a>";
            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No genres found</td></tr>";
    }
    ?>
</table>

<?php
// DELETE operation: Delete a genre
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM Genres WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Genre deleted successfully!";
        header("Location:".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// UPDATE operation: Edit a genre
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM Genres WHERE id=$id");
    $row = $result->fetch_assoc();

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $sql = "UPDATE Genres SET name='$name' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "Genre updated successfully!";
            header("Location:".$_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
    ?>

    <h2>Edit Genre</h2>
    <form method="POST">
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
        <button type="submit" name="update">Update Genre</button>
    </form>

    <?php
}

$conn->close();
?>

</body>
</html>