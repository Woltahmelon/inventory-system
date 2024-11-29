<?php
include 'db.php'; // Include database connection

// Handle form submission to insert a product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['insert'])) {
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $price = $_POST['price'];
        $expiration_date = $_POST['expiration_date']; // Get the expiration date

        // Insert product into the database
        $sql = "INSERT INTO products (name, amount, price, expiration_date) VALUES ('$name', '$amount', '$price', '$expiration_date')";
        if ($conn->query($sql) === TRUE) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Handle product deletion
    if (isset($_POST['delete'])) {
        $id = $_POST['product_id'];
        $sql = "DELETE FROM products WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Product deleted successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Handle clear inventory
    if (isset($_POST['clear_inventory'])) {
        $sql = "DELETE FROM products"; // Delete all products
        if ($conn->query($sql) === TRUE) {
            // Reset the auto-increment ID to 1
            $resetSql = "ALTER TABLE products AUTO_INCREMENT = 1";
            if ($conn->query($resetSql) === TRUE) {
                echo "Inventory cleared and ID reset successfully!";
            } else {
                echo "Error resetting ID: " . $conn->error;
            }
        } else {
            echo "Error clearing inventory: " . $conn->error;
        }
    }

    // Handle product update
    if (isset($_POST['update'])) {
        $id = $_POST['product_id'];
        $name = $_POST['name'];
        $amount = $_POST['amount'];
        $price = $_POST['price'];
        $expiration_date = $_POST['expiration_date'];
        
        $sql = "UPDATE products SET name='$name', amount='$amount', price='$price', expiration_date='$expiration_date' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            echo "Product updated successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Check for sort order and the column for sorting (ID, Price, Amount, Expiration Date)
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'asc'; // Default to ascending
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'price'; // Default to sorting by price

// Modify the SQL query based on the sorting option chosen
$sql = "SELECT * FROM products ORDER BY $sort_by $sort_order";

// Fetch all products from the database
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 20px;
            text-align: center;
            justify-content: center;
            align-items: center;
        }
        
        h1, h2 {
            font-size: 2em;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 0;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="submit"] {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            padding: 10px;
            margin: 5px;
            border: 2px solid #333;
            border-radius: 4px;
            width: 200px;
        }

        input[type="submit"] {
            background-color: #444; /* Dark gray */
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #555; /* Slightly lighter dark gray */
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            border: 2px solid #333;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #444; /* Dark gray */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        tr.out-of-stock {
            background-color: red;
            color: white;
        }

        .pixel-btn {
            background-color: #444; /* Dark gray */
            color: #fff;
            border: none;
            font-family: 'Courier New', monospace;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s ease;
        }

        .pixel-btn:hover {
            background-color: #555; /* Slightly lighter gray */
        }

        .pixel-btn:active {
            background-color: #333; /* Darkest gray when pressed */
            box-shadow: 0 3px 0 #222 inset;
        }

    </style>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this product?");
        }
    </script>
</head>
<body>
    
    <h1>Inventory Management System</h1>

    <!-- Logout Button -->
    <a href="login.php">
        <button class="pixel-btn">Logout</button>
    </a>

    <!-- Insert Product Form -->
    <h2>Insert Product</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required><br>
        <input type="number" name="amount" placeholder="Amount" required><br>
        <input type="number" step="0.01" name="price" placeholder="Price" required><br>
        <input type="date" name="expiration_date" placeholder="Expiration Date" required><br>
        <input type="submit" name="insert" value="Add Product" class="pixel-btn">
    </form>

    <!-- Clear Inventory Form -->
    <form method="POST">
        <input type="submit" name="clear_inventory" value="Clear Inventory" class="pixel-btn">
    </form>

    <!-- Product List and Sorting -->
    <h2>Product List</h2>
    <form method="GET" style="margin-bottom: 20px;">
        <label for="sort_by">Sort by: </label>
        <select name="sort_by" id="sort_by">
            <option value="price" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price') ? 'selected' : ''; ?>>Price</option>
            <option value="id" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'id') ? 'selected' : ''; ?>>ID</option>
            <option value="amount" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'amount') ? 'selected' : ''; ?>>Amount</option>
            <option value="expiration_date" <?php echo (isset($_GET['sort_by']) && $_GET['sort_by'] == 'expiration_date') ? 'selected' : ''; ?>>Expiration Date</option>
        </select>
        
        <label for="sort_order">Sort order: </label>
        <select name="sort_order" id="sort_order">
            <option value="asc" <?php echo (isset($_GET['sort_order']) && $_GET['sort_order'] == 'asc') ? 'selected' : ''; ?>>Ascending</option>
            <option value="desc" <?php echo (isset($_GET['sort_order']) && $_GET['sort_order'] == 'desc') ? 'selected' : ''; ?>>Descending</option>
        </select>
        
        <input type="submit" value="Sort" class="pixel-btn">
    </form>

    <!-- Product List Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Price</th>
                <th>Expiration Date</th> <!-- Display Expiration Date -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr class="<?php echo ($row['amount'] == 0) ? 'out-of-stock' : ''; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['expiration_date']; ?></td> <!-- Show Expiration Date -->
                    <td>
                        <!-- Edit Product Form -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
                            <input type="number" name="amount" value="<?php echo $row['amount']; ?>" required>
                            <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required>
                            <input type="date" name="expiration_date" value="<?php echo $row['expiration_date']; ?>" required>
                            <input type="submit" name="update" value="Update" class="pixel-btn">
                        </form>
                        
                        <!-- Delete Product Form -->
                        <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="submit" name="delete" value="Delete" class="pixel-btn">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Checkout Button -->
    <h2>Proceed to Checkout</h2>
    <a href="checkout.php"><button class="pixel-btn">Go to Checkout</button></a>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
