


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['ShipperID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $ShipperID = isset($_POST['ShipperID']) ? $_POST['ShipperID'] : NULL;
        $ShipperName = isset($_POST['ShipperName']) ? $_POST['ShipperName'] : '';
        $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';
     

  
  
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE shippers SET ShipperID = ?, ShipperName = ?,  Phone = ? WHERE ShipperID = ?');
        $stmt->execute([$ShipperID, $ShipperName,  $Phone, $_GET['ShipperID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM shippers WHERE ShipperID = ?');
    $stmt->execute([$_GET['ShipperID']]);
    $shippers = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$shippers) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Shippers #<?=$shippers['ShipperID']?></h2>
    <form action="../Shippers/update.php?ShipperID=<?=$shippers['ShipperID']?>" method="post">
        <label for="ShipperID">ShipperID </label>
        <label for="ShipperName"> Shipper Name</label>
        <input type="text" name="ShipperID" placeholder="1" value="<?=$shippers['ShipperID']?>" ShipperID ="ShipperID ">
        <input type="text" name="ShipperName" placeholder="" value="<?=$shippers['ShipperName']?>" ShipperID ="ShipperName">

        <label for="Phone">Phone</label>
        <label for="CategoryID"></label>

        <input type="text" name="Phone" placeholder="" value="<?=$shippers['Phone']?>" ShipperID ="Phone">
  




        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Shippers/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>