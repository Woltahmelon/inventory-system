<?php
session_start();

// Pixelated design for displaying the records
echo "<style>
    /* Importing the font correctly */
    @import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');

    body {
        font-family: 'Courier New', monospace;
        background-color: #f0f0f0;
        color: #333;
        margin: 0;
        padding: 20px;
        text-align: center;
    }
    .pixel-heading {
        font-size: 2em;
        font-family: 'Press Start 2P', cursive;
        color: #333;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
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
    .pixel-text {
        font-family: 'Courier New', monospace;
        font-size: 1em;
        color: #333;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Minecraft-style Dark Gray Button Styling */
    .pixel-btn {
        background-color: #444; /* Dark gray background */
        color: #fff; /* Light text for contrast */
        border: 3px solid #333; /* Dark border */
        font-family: 'Courier New', monospace;
        padding: 12px 30px;
        font-size: 18px;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 2px;
        box-shadow: 0 2px 0 #333 inset; /* Inner shadow to give depth */
        transition: all 0.2s ease;
        display: inline-block;
        margin: 10px;
        width: 200px; /* Fixed width for uniform button size */
    }

    /* Darker gray on hover effect */
    .pixel-btn:hover {
        background-color: #555; /* Slightly lighter dark gray on hover */
        border-color: #444; /* Slightly lighter border on hover */
        box-shadow: 0 3px 0 #222 inset; /* Slightly deeper shadow on hover */
    }

    /* Focus effect when button is clicked (using a box-shadow effect instead of translate) */
    .pixel-btn:active {
        background-color: #333; /* Darkest gray when pressed */
        border-color: #222; /* Darker border color on press */
        box-shadow: 0 0 5px #111 inset; /* Simulate pressing the button */
    }

    a {
        text-decoration: none;
    }
</style>";

$filename = 'checkout_records.txt';

if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);

    if (count($lines) > 0) {
        echo "<h2 class='pixel-heading'>Checkout Records</h2>";
        echo "<form method='POST' action='delete_checkout_record.php'>";
        echo "<table class='pixel-table'>";
        echo "<thead><tr><th class='pixel-text'>Product</th><th class='pixel-text'>Amount</th><th class='pixel-text'>Total</th><th class='pixel-text'>Time Added</th><th class='pixel-text'>Action</th></tr></thead><tbody>";

        foreach ($lines as $index => $line) {
            if ($line !== '') {
                // Split the line to get product name, amount, total price, and timestamp
                preg_match('/Product: (.*), Amount: (\d+), Total: ₱(\d+(\.\d{2})?), Time: (.*)/', $line, $matches);

                if (isset($matches[1])) {
                    $product_name = $matches[1];
                    $amount = $matches[2]; // Changed from quantity to amount
                    $total_price = $matches[3];
                    $timestamp = $matches[5];

                    echo "<tr>";
                    echo "<td class='pixel-text'>{$product_name}</td>";
                    echo "<td class='pixel-text'>{$amount}</td>"; // Changed to show amount
                    echo "<td class='pixel-text'>₱{$total_price}</td>";
                    echo "<td class='pixel-text'>{$timestamp}</td>";
                    echo "<td class='pixel-text'><input type='radio' name='delete_record' value='{$index}'> Select</td>";
                    echo "</tr>";
                }
            }
        }

        echo "</tbody></table>";
        echo "<button type='submit' class='pixel-btn'>Delete Selected</button>";
        echo "</form>";

        // Delete all button
        echo "<form method='POST' action='delete_checkout_record.php'>";
        echo "<button type='submit' name='delete_all' class='pixel-btn'>Delete All Records</button>";
        echo "</form>";
    } else {
        echo "<p class='pixel-text'>No records found.</p>";
    }
} else {
    echo "<p class='pixel-text'>No records found.</p>";
}

echo '<br><a href="checkout.php"><button class="pixel-btn">Go Back to Checkout</button></a>';
echo '<br><a href="index.php"><button class="pixel-btn">Go Back to Index</button></a>';
?>
