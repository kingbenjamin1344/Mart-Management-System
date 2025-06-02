<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch all categories from the database
$stmt_categories = $pdo->prepare('SELECT CategoryID, CategoryName FROM categories');
$stmt_categories->execute();
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty, insert new records

    // Set up variables for the products table
    $ProductID = isset($_POST['ProductID']) && !empty($_POST['ProductID']) && $_POST['ProductID'] != 'auto' ? $_POST['ProductID'] : NULL;
    $ProductName = isset($_POST['ProductName']) ? $_POST['ProductName'] : '';
    $CategoryName = isset($_POST['CategoryName']) ? $_POST['CategoryName'] : '';
    $Unit = isset($_POST['Unit']) ? $_POST['Unit'] : '';
    $Price = isset($_POST['Price']) ? $_POST['Price'] : '';

    // Find the CategoryID based on the selected CategoryName
    $stmt_category_id = $pdo->prepare('SELECT CategoryID FROM categories WHERE CategoryName = ?');
    $stmt_category_id->execute([$CategoryName]);
    $category = $stmt_category_id->fetch(PDO::FETCH_ASSOC);
    $CategoryID = $category['CategoryID'];

    // Insert new record into the products table
    $stmt = $pdo->prepare('INSERT INTO products (ProductID, ProductName, CategoryID, Unit, Price) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$ProductID, $ProductName, $CategoryID, $Unit, $Price]);

    // Output message
    $msg = 'Created Successfully!';
}
?>

   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .content h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .content form {
            display: flex;
            flex-direction: column;
        }
        .content form label {
            margin-top: 10px;
        }
        .content form input, .content form select {
            margin-top: 5px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .content form input[type="submit"] {
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .content form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .content a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .content a:hover {
            text-decoration: underline;
        }
        .content p {
            text-align: center;
            color: red;
        }
    </style>
</head>
<body>
    <div class="content update">
        <h2>Create Product</h2>
        <form action="create.php" method="post">
            <label for="ProductID">Product ID</label>
            <input type="text" name="ProductID" placeholder="auto" id="ProductID">
            
            <label for="ProductName">Product Name</label>
            <input type="text" name="ProductName" placeholder="Enter product name" id="ProductName">
            
            <label for="CategoryName">Category Name</label>
            <select name="CategoryName" id="CategoryName">
                <?php foreach ($categories as $category): ?>
                    <option value="<?=$category['CategoryName']?>"><?=$category['CategoryName']?></option>
                <?php endforeach; ?>
            </select>
            
            <label for="Unit">Unit</label>
            <input type="text" name="Unit" placeholder="Enter unit (e.g., kg, liter)" id="Unit">
            
            <label for="Price">Price</label>
            <input type="text" name="Price" placeholder="Enter price" id="Price">

            <input type="submit" value="Create">
        </form>
        <?php if ($msg): ?>
        <p><?=$msg?></p>
        <?php endif; ?>
        <a href="read.php" onclick="window.history.back();">Back</a>
    </div>
</body>
</html>
