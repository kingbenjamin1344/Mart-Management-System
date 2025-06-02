
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $OrderDetailID = isset($_POST['OrderDetailID']) && !empty($_POST['OrderDetailID']) && $_POST['OrderDetailID'] != 'auto' ? $_POST['OrderDetailID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $OrderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
    $ProductID = isset($_POST['ProductID']) ? $_POST['ProductID'] : '';
    $Quantity = isset($_POST['Quantity']) ? $_POST['Quantity'] : '';



    
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO order_details VALUES (?, ?, ?, ?)');
    $stmt->execute([$OrderDetailID, $OrderID, $ProductID, $Quantity ]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create order details</h2>
    <form action="../Order_Details/create.php" method="post">
        <label for="OrderDetailID">Order ID</label>
        <label for="OrderID">OrderID</label>
        <input type="text" name="OrderDetailID" placeholder="" value="auto" OrderDetailID="OrderDetailID">
        <input type="text" name="OrderID" placeholder=" " OrderDetailID="OrderID">
        <label for="ProductID">Product ID </label>
        <label for="Quantity">Quantity</label>
        <input type="text" name="ProductID" placeholder="" OrderDetailID="ProductID">
        <input type="text" name="Quantity" placeholder="" OrderDetailID="Quantity">

    

        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Order_Details/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

