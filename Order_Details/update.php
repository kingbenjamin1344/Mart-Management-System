


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['OrderDetailID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $OrderDetailID = isset($_POST['OrderDetailID']) ? $_POST['OrderDetailID'] : NULL;
        $OrderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : '';
        $ProductID = isset($_POST['ProductID']) ? $_POST['ProductID'] : '';
        $Quantity = isset($_POST['Quantity']) ? $_POST['Quantity'] : '';



  
  
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE order_details SET OrderDetailID = ?, OrderID = ?,  ProductID = ?, Quantity  = ?   WHERE OrderDetailID = ?');
        $stmt->execute([$OrderDetailID, $OrderID,  $ProductID, $Quantity,  $_GET['OrderDetailID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM order_details WHERE OrderDetailID = ?');
    $stmt->execute([$_GET['OrderDetailID']]);
    $order_details = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order_details) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Order Detail #<?=$order_details['OrderDetailID']?></h2>
    <form action="../Order_Details/update.php?OrderDetailID=<?=$order_details['OrderDetailID']?>" method="post">
        <label for="OrderDetailID">OrderID </label>
        <label for="OrderID">Order ID</label>
        <input type="text" name="OrderDetailID" placeholder="1" value="<?=$order_details['OrderDetailID']?>" OrderDetailID ="OrderDetailID ">
        <input type="text" name="OrderID" placeholder="" value="<?=$order_details['OrderID']?>" OrderDetailID ="OrderID">

        <label for="ProductID">ProductID</label>
        <label for="Quantity">Quantity</label>

        <input type="text" name="ProductID" placeholder="" value="<?=$order_details['ProductID']?>" OrderDetailID ="ProductID">
        <input type="text" name="Quantity" placeholder="" value="<?=$order_details['Quantity']?>" OrderDetailID ="Quantity">






        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Order_Details/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>