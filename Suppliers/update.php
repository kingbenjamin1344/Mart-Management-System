


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['SupplierID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $SupplierID = isset($_POST['SupplierID']) ? $_POST['SupplierID'] : NULL;
        $SupplierName = isset($_POST['SupplierName']) ? $_POST['SupplierName'] : '';
        $ContactName = isset($_POST['ContactName']) ? $_POST['ContactName'] : '';
        $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
        $City = isset($_POST['City']) ? $_POST['City'] : '';
        $PostalCode = isset($_POST['PostalCode']) ? $_POST['PostalCode'] : '';
        $Country = isset($_POST['Country']) ? $_POST['Country'] : '';
        $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';
  
  
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE suppliers SET SupplierID = ?, SupplierName = ?,  ContactName = ?, Address  = ? , City = ? , PostalCode  = ?, Country  = ? , Phone = ? WHERE SupplierID = ?');
        $stmt->execute([$SupplierID, $SupplierName, $ContactName,  $Address, $City,  $PostalCode, $Country,$Phone,  $_GET['SupplierID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM suppliers WHERE SupplierID = ?');
    $stmt->execute([$_GET['SupplierID']]);
    $suppliers = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$suppliers) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Supplier #<?=$suppliers['SupplierID']?></h2>
    <form action="../Suppliers/update.php?SupplierID=<?=$suppliers['SupplierID']?>" method="post">
        <label for="SupplierID">SupplierID </label>
        <label for="SupplierName">Customer Name</label>
        <input type="text" name="SupplierID" placeholder="1" value="<?=$suppliers['SupplierID']?>" SupplierID ="SupplierID ">
        <input type="text" name="SupplierName" placeholder="" value="<?=$suppliers['SupplierName']?>" SupplierID ="SupplierName">

        <label for="ContactName">ContactName</label>
        <label for="Address">Address</label>

        <input type="text" name="ContactName" placeholder="" value="<?=$suppliers['ContactName']?>" SupplierID ="ContactName">
        <input type="text" name="Address" placeholder="" value="<?=$suppliers['Address']?>" SupplierID ="Address">

        <label for="City">City</label>
        <label for="PostalCode">PostalCode</label>

        <input type="text" name="City" placeholder="" value="<?=$suppliers['City']?>" CustomerID ="City">
        <input type="text" name="PostalCode" placeholder="" value="<?=$suppliers['PostalCode']?>" SupplierID ="PostalCode">


        <label for="Country">Country</label>
        <label for="Phone">Phone</label>

        <input type="text" name="Country" placeholder="" value="<?=$suppliers['Country']?>" SupplierID ="Country">
        <input type="text" name="Phone" placeholder="" value="<?=$suppliers['Phone']?>" SupplierID ="Phone">

        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Suppliers/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>