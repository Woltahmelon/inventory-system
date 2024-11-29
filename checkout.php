<?php
include 'db.php'; // Include database connection

// Start the session
session_start();

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['checkout'])) {
    $total_price = 0;
    $cart = [];
    
    // Start transaction to ensure data integrity
    $conn->begin_transaction();
    
    try {
        $is_any_quantity_selected = false;

        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if ($quantity > 0) {
                $is_any_quantity_selected = true;  // Mark that a quantity was selected

                // Fetch product details
                $sql = "SELECT * FROM products WHERE id = $product_id";
                $result = $conn->query($sql);
                $product = $result->fetch_assoc();

                // Calculate the total price for this product
                $total_price += $product['price'] * $quantity;

                // Add to cart
                $cart[] = [
                    'name' => $product['name'],
                    'amount' => $quantity,
                    'price' => $product['price'],
                    'total' => $product['price'] * $quantity
                ];

                // Update product stock after purchase
                $new_amount = $product['amount'] - $quantity;
                $update_sql = "UPDATE products SET amount = $new_amount WHERE id = $product_id";
                if (!$conn->query($update_sql)) {
                    throw new Exception("Error updating product stock.");
                }
            }
        }

        if (!$is_any_quantity_selected) {
            echo "<script>alert('Please select at least one product to proceed with the checkout.');</script>";
        } else {
            // Commit the transaction
            $conn->commit();

            // Store the cart and total price in the session
            $_SESSION['cart'] = $cart;
            $_SESSION['total_price'] = $total_price;

            // Include the checkout summary
            include 'checkout_summary.php';  // Include the checkout summary display
        }
    } catch (Exception $e) {
        // If something went wrong, rollback the transaction
        $conn->rollback();
        echo "<p>Error processing the checkout. Please try again later.</p>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }

    exit; // Stop further processing
}

// Fetch products from the database for the checkout page
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 20px;
            text-align: center;
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
            background-color: #444;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e0e0e0;
        }
        .pixel-btn {
            background-color: #333;
            color: #fff;
            border: none;
            font-family: 'Courier New', monospace;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .pixel-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Checkout Page</h1>
    <form method="POST" onsubmit="return validateForm()">
        <h2>Select Products to Checkout</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Amount Available</th>
                    <th>Price</th>
                    <th>Quantity to Buy</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td>₱<?php echo $row['price']; ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo $row['id']; ?>]" min="0" max="<?php echo $row['amount']; ?>" value="0">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <input type="submit" name="checkout" value="Proceed to Checkout" class="pixel-btn">
    </form>

    <!-- Buttons to navigate to other pages -->
    <br><br>
    
    <a href="index.php"><button class="pixel-btn">Go Back to Index</button></a>
    <a href="sales_report.php"><button class="pixel-btn">View Sales Report</button></a>
   <a href="checkout_records.php"><button class="pixel-btn">Checkout Records</button></a>

    <script>
        // Function to validate if at least one product has been selected
        function validateForm() {
            let quantities = document.querySelectorAll("input[type='number']");
            let isValid = false;
            for (let i = 0; i < quantities.length; i++) {
                if (quantities[i].value > 0) {
                    isValid = true;
                    break;
                }
            }
            if (!isValid) {
                alert("Please select at least one product to proceed with the checkout.");
                return false;  // Prevent form submission
            }
            return true;  // Allow form submission
        }
    </script>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
