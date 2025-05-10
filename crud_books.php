<?php
// Database connection settings
include_once("connection.php");

// CREATE: Add a new book
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre_id = $_POST['genre_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $isbn = $_POST['isbn'];
    $description = $_POST['description'];
    $cover_image_url = $_POST['cover_image_url'];

    // Insert book into the database
    $sql = "INSERT INTO Books (title, author, genre_id, price, stock, isbn, description, cover_image_url)
            VALUES ('$title', '$author', '$genre_id', '$price', '$stock', '$isbn', '$description', '$cover_image_url')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// UPDATE: Edit a book's details
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre_id = $_POST['genre_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $isbn = $_POST['isbn'];
    $description = $_POST['description'];
    $cover_image_url = $_POST['cover_image_url'];

    // Update the book in the database
    $sql = "UPDATE Books SET title='$title', author='$author', genre_id='$genre_id', price='$price', stock='$stock', isbn='$isbn', description='$description', cover_image_url='$cover_image_url' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to the same page after update
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// DELETE: Delete a book
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM Books WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully!";
        header("Location: ".$_SERVER['PHP_SELF']); // Redirect to the same page after deletion
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// READ: Display all books and their genres
$sql = "SELECT Books.id, Books.title, Books.author, Genres.name AS genre_name, Books.price, Books.stock
        FROM Books JOIN Genres ON Books.genre_id = Genres.id";
$result = $conn->query($sql);

// Handle edit request
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $sql_edit = "SELECT * FROM Books WHERE id = $edit_id";
    $result_edit = $conn->query($sql_edit);
    $book = $result_edit->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books CRUD Application</title>
</head>
<body>

<h1>Books CRUD Application</h1>

<!-- Form to add a new book -->
<h2>Add a New Book</h2>
<form method="POST">
    <label for="title">Book Title:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br>

    <label for="genre_id">Genre:</label>
    <select id="genre_id" name="genre_id" required>
        <?php
        // Fetch genres for dropdown
        $genres_result = $conn->query("SELECT * FROM Genres");
        while ($genre = $genres_result->fetch_assoc()) {
            echo "<option value='" . $genre['id'] . "'>" . $genre['name'] . "</option>";
        }
        ?>
    </select><br>

    <label for="price">Price:</label>
    <input type="text" id="price" name="price" required><br>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" required><br>

    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="isbn" required><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="cover_image_url">Cover Image URL:</label>
    <input type="text" id="cover_image_url" name="cover_image_url"><br>

    <button type="submit" name="create">Add Book</button>
</form>

<!-- Form to update an existing book -->
<?php if (isset($edit_id)): ?>
    <h2>Edit Book</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $book['title']; ?>" required><br>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" value="<?php echo $book['author']; ?>" required><br>

        <label for="genre_id">Genre:</label>
        <select id="genre_id" name="genre_id" required>
            <?php
            // Fetch genres for dropdown
            $genres_result = $conn->query("SELECT * FROM Genres");
            while ($genre = $genres_result->fetch_assoc()) {
                $selected = ($genre['id'] == $book['genre_id']) ? 'selected' : '';
                echo "<option value='" . $genre['id'] . "' $selected>" . $genre['name'] . "</option>";
            }
            ?>
        </select><br>

        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $book['price']; ?>" required><br>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $book['stock']; ?>" required><br>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>" required><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $book['description']; ?></textarea><br>

        <label for="cover_image_url">Cover Image URL:</label>
        <input type="text" id="cover_image_url" name="cover_image_url" value="<?php echo $book['cover_image_url']; ?>"><br>

        <button type="submit" name="update">Update Book</button>
    </form>
<?php endif; ?>

<!-- Display all books -->
<h2>All Books</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['author'] . "</td><td>" . $row['genre_name'] . "</td><td>" . $row['price'] . "</td><td>" . $row['stock'] . "</td><td>";
            echo "<a href='?edit=" . $row['id'] . "'>Edit</a> | ";
            echo "<a href='?delete=" . $row['id'] . "'>Delete</a>";
            echo "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No books found</td></tr>";
    }
    ?>
</table>

<?php
$conn->close();
?>
<br/>
<a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
