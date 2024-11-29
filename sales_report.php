<?php
include 'db.php'; // Include database connection

// Fetch products and their sales data
$sql = "SELECT id, name, price, amount FROM products";
$result = $conn->query($sql);

$fast_moving = [];
$slow_moving = [];
$sales_threshold = 20;  // Define a threshold to classify products as fast-moving or slow-moving

// Classify products based on amount
while ($row = $result->fetch_assoc()) {
    if ($row['amount'] < $sales_threshold) {
        $fast_moving[] = $row;
    } else {
        $slow_moving[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
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

<h1>Sales Report</h1>

<h2>Fast Moving Products</h2>
<table>
    <thead>
        <tr><th>Product ID</th><th>Product Name</th><th>Price</th><th>Stock</th></tr>
    </thead>
    <tbody>
        <?php foreach ($fast_moving as $product) { ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td>₱<?php echo $product['price']; ?></td>
                <td><?php echo $product['amount']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Slow Moving Products</h2>
<table>
    <thead>
        <tr><th>Product ID</th><th>Product Name</th><th>Price</th><th>Stock</th></tr>
    </thead>
    <tbody>
        <?php foreach ($slow_moving as $product) { ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td>₱<?php echo $product['price']; ?></td>
                <td><?php echo $product['amount']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<a href="index.php"><button class="pixel-btn">Go Back to Index</button></a>
<a href="checkout.php"><button class="pixel-btn">Go Back to Checkout</button></a>

</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
