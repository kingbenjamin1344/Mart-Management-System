<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';

// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['ProductID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $ProductID = isset($_POST['ProductID']) ? $_POST['ProductID'] : NULL;
        $ProductName = isset($_POST['ProductName']) ? $_POST['ProductName'] : '';
        $SupplierID = isset($_POST['SupplierID']) ? $_POST['SupplierID'] : '';
        $CategoryID = isset($_POST['CategoryID']) ? $_POST['CategoryID'] : '';
        $Unit = isset($_POST['Unit']) ? $_POST['Unit'] : '';
        $Price = isset($_POST['Price']) ? $_POST['Price'] : '';

        // Update the record
        $stmt = $pdo->prepare('UPDATE products SET ProductID = ?, ProductName = ?, SupplierID = ?, CategoryID = ?, Unit = ?, Price = ? WHERE ProductID = ?');
        $stmt->execute([$ProductID, $ProductName, $SupplierID, $CategoryID, $Unit, $Price, $_GET['ProductID']]);
        $msg = 'Updated Successfully!';
    }

    // Get the product details from the products table
    $stmt = $pdo->prepare('SELECT * FROM products WHERE ProductID = ?');
    $stmt->execute([$_GET['ProductID']]);
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get the category details from the categories table
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE CategoryID = ?');
    $stmt->execute([$products['CategoryID']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$products) {
        exit('Product doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Update')?>

<div class="content update">
    <h2>Update Product #<?=$products['ProductID']?></h2>
    <form action="../Products/update.php?ProductID=<?=$products['ProductID']?>" method="post">
        <label for="ProductID">ProductID</label>
        <label for="ProductName">Product Name</label>
        <input type="text" name="ProductID" placeholder="1" value="<?=$products['ProductID']?>" id="ProductID">
        <input type="text" name="ProductName" placeholder="" value="<?=$products['ProductName']?>" id="ProductName">

        <label for="SupplierID">Supplier ID</label>
        <label for="CategoryID">Category ID</label>
        <input type="text" name="SupplierID" placeholder="" value="<?=$products['SupplierID']?>" id="SupplierID">
        <input type="text" name="CategoryID" placeholder="" value="<?=$products['CategoryID']?>" id="CategoryID">

        <label for="Unit">Unit</label>
        <label for="Price">Price</label>
        <input type="text" name="Unit" placeholder="" value="<?=$products['Unit']?>" id="Unit">
        <input type="text" name="Price" placeholder="" value="<?=$products['Price']?>" id="Price">

        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Products/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>
