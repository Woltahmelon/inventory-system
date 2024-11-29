<?php

// Ensure that cart and total_price are set in the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = isset($_SESSION['total_price']) ? $_SESSION['total_price'] : 0;

// Check if the cart is empty
if (count($cart) > 0) {
    // Display the checkout summary
    echo "<h2 class='pixel-heading'>Checkout Summary</h2>";
    echo "<table class='pixel-table'>";
    echo "<thead><tr><th class='pixel-text'>Product</th><th class='pixel-text'>Amount</th><th class='pixel-text'>Total</th><th class='pixel-text'>Time Added</th></tr></thead><tbody>";

    // Loop through each cart item and display its details
    foreach ($cart as $item) {
        // Sanitize output to avoid XSS
        $product_name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
        $amount = (int)$item['amount']; // Ensure amount is an integer
        $total = (float)$item['total']; // Total price for the product (amount * unit price)
        $timestamp = date("Y-m-d H:i:s"); // Get the current date and time for when the product was added

        echo "<tr>";
        echo "<td class='pixel-text'>{$product_name}</td>";
        echo "<td class='pixel-text'>{$amount}</td>"; // Display the amount
        echo "<td class='pixel-text'>₱" . number_format($total, 2) . "</td>"; // Display the total price
        echo "<td class='pixel-text'>{$timestamp}</td>"; // Display timestamp
        echo "</tr>";

        // Save the checkout summary with the timestamp and total price to a file (checkout_records.txt)
        $filename = 'checkout_records.txt';
        $checkout_data = "Product: {$item['name']}, Amount: {$item['amount']}, Total: ₱{$item['total']}, Time: {$timestamp}\n";
        file_put_contents($filename, $checkout_data, FILE_APPEND);
    }

    echo "</tbody></table>";
    // Display the total price
    echo "<h3 class='pixel-heading'>Total Price: ₱" . number_format($total_price, 2) . "</h3>";

    echo "<h3 class='pixel-heading'>Done!</h3>";

    // Provide navigation buttons to go back to checkout, index, or view sales report
    echo '<br><a href="checkout.php"><button class="pixel-btn">Go Back to Checkout</button></a>';
    echo '<br><a href="index.php"><button class="pixel-btn">Go Back to Index</button></a>';
    echo '<br><a href="sales_report.php"><button class="pixel-btn">View Sales Report</button></a>';
    echo '<br><a href="checkout_records.php"><button class="pixel-btn">Checkout Records</button></a>';
} else {
    // If no items are in the cart
    echo "<p class='pixel-text'>No items in the cart to display.</p>";
}
?>

<!-- Retain Pixelated Style for Checkout -->
<style>
    /* Import custom pixelated font */
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    /* General body styling */
    body {
        font-family: 'Courier New', monospace;
        background-color: #f0f0f0;
        color: #333;
        margin: 0;
        padding: 20px;
        text-align: center;
    }

    /* Pixelated heading */
    .pixel-heading {
        font-size: 2em;
        font-family: 'Press Start 2P', cursive;
        color: #333;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* Pixelated table */
    .pixel-table {
        width: 80%;
        margin: 0 auto;
        border-collapse: collapse;
        font-family: 'Courier New', monospace;
        border: 2px solid #333;
    }

    .pixel-table th, .pixel-table td {
        padding: 10px;
        text-align: center;
        border: 2px solid #333;
        font-size: 1.2em;
    }

    .pixel-table th {
        background-color: #444;
        color: white;
    }

    .pixel-table tr:nth-child(even) {
        background-color: #e9e9e9;
    }

    .pixel-table tr:hover {
        background-color: #d4d4d4;
    }

    /* Pixelated text for product, price, etc */
    .pixel-text {
        font-family: 'Courier New', monospace;
        font-size: 1em;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Pixelated buttons */
    .pixel-btn {
        background-color: #444; /* Dark gray background */
        color: #fff;
        border: none;
        font-family: 'Courier New', monospace;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: background-color 0.3s ease;
    }

    .pixel-btn:hover {
        background-color: #555; /* Lighter gray on hover */
    }

    .pixel-btn:active {
        background-color: #333; /* Darkest gray on click */
        box-shadow: 0 3px 0 #222 inset; /* Button press effect */
    }

    /* Button container styling */
    a {
        text-decoration: none;
        margin: 10px;
    }
</style>
