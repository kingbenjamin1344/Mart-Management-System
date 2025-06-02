


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['OrderID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $OrderID = isset($_POST['OrderID']) ? $_POST['OrderID'] : NULL;
        $CustomerID = isset($_POST['CustomerID']) ? $_POST['CustomerID'] : '';
        $EmployeeID = isset($_POST['EmployeeID']) ? $_POST['EmployeeID'] : '';
        $OrderDate = isset($_POST['OrderDate']) ? $_POST['OrderDate'] : '';
        $ShipperID = isset($_POST['ShipperID']) ? $_POST['ShipperID'] : '';


  
  
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE orders SET OrderID = ?, CustomerID = ?,  EmployeeID = ?, OrderDate  = ? , ShipperID = ?  WHERE OrderID = ?');
        $stmt->execute([$OrderID, $CustomerID,  $EmployeeID, $OrderDate,  $ShipperID,  $_GET['OrderID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE OrderID = ?');
    $stmt->execute([$_GET['OrderID']]);
    $orders = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$orders) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Order #<?=$orders['OrderID']?></h2>
    <form action="../Order/update.php?OrderID=<?=$orders['OrderID']?>" method="post">
        <label for="OrderID">OrderID </label>
        <label for="CustomerID">CustomerID</label>
        <input type="text" name="OrderID" placeholder="1" value="<?=$orders['OrderID']?>" OrderID ="OrderID ">
        <input type="text" name="CustomerID" placeholder="" value="<?=$orders['CustomerID']?>" OrderID ="CustomerID">

        <label for="EmployeeID">EmployeeID</label>
        <label for="OrderDate">OrderDate</label>

        <input type="text" name="EmployeeID" placeholder="" value="<?=$orders['EmployeeID']?>" OrderID ="EmployeeID">
        <input type="date" name="OrderDate" placeholder="" value="<?=$orders['OrderDate']?>" OrderID ="OrderDate">

        <label for="ShipperID">ShipperID</label>
        <label for=""></label>

        <input type="text" name="ShipperID" placeholder="" value="<?=$orders['ShipperID']?>" OrderID ="ShipperID">




        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Order/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>