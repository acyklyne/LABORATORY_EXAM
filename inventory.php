<!-- File: inventory.php -->
<?php
// Start session to maintain the inventory across page reloads
session_start();

// Initialize the inventory if not already set
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    
    // Validation: Item name should not be empty, and quantity should be positive
    if (empty($itemName)) {
        $error_message = "Item name cannot be blank!";
    } elseif ($quantity <= 0) {
        $error_message = "Quantity must be greater than zero!";
    } elseif (array_key_exists($itemName, $_SESSION['inventory'])) {
        $error_message = "Item already exists in the inventory!";
    } else {
        // Add the item to the inventory
        $_SESSION['inventory'][$itemName] = $quantity;
        $success_message = "Item added successfully!";
    }
}

// Handle search
$searchResult = null;
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    if (array_key_exists($searchQuery, $_SESSION['inventory'])) {
        $searchResult = [
            'name' => $searchQuery,
            'quantity' => $_SESSION['inventory'][$searchQuery]
        ];
    } else {
        $searchError = "Product not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
</head>
<body>
    <h1>Inventory Dashboard</h1>

    <!-- Display success or error messages -->
    <?php if (!empty($error_message)) : ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php elseif (!empty($success_message)) : ?>
        <p style="color:green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <!-- Add Item Form -->
    <h2>Add New Item</h2>
    <form method="POST" action="inventory.php">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>
        <br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
        <br><br>
        <button type="submit">Add Item</button>
    </form>

    <!-- Inventory Table -->
<h2>Current Inventory</h2>
    <table border="1">
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($_SESSION['inventory'] as $name => $qty) : ?>
            <tr>
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($qty); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Search Form -->
    <h2>Search Item</h2>
    <form method="GET" action="inventory.php">
        <label for="search">Item Name:</label>
        <input type="text" id="search" name="search" required>
        <button type="submit">Search</button>
    </form>

    <!-- Search Results -->
    <?php if ($searchResult) : ?>
        <p>Item: <?php echo htmlspecialchars($searchResult['name']); ?> | Quantity: <?php echo htmlspecialchars($searchResult['quantity']); ?></p>
    <?php elseif (!empty($searchError)) : ?>
        <p style="color:red;"><?php echo $searchError; ?></p>
    <?php endif; ?>
</body>
</html>