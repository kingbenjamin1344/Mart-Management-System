


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['CustomerID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $CustomerID = isset($_POST['CustomerID']) ? $_POST['CustomerID'] : NULL;
        $CustomerName = isset($_POST['CustomerName']) ? $_POST['CustomerName'] : '';
        $ContactName = isset($_POST['ContactName']) ? $_POST['ContactName'] : '';
        $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
        $City = isset($_POST['City']) ? $_POST['City'] : '';
        $PostalCode = isset($_POST['PostalCode']) ? $_POST['PostalCode'] : '';
        $Country = isset($_POST['Country']) ? $_POST['Country'] : '';
  
  
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE customer SET CustomerID = ?, CustomerName = ?,  ContactName = ?, Address  = ? , City = ? , PostalCode  = ?, Country  = ? WHERE CustomerID = ?');
        $stmt->execute([$CustomerID, $CustomerName, $ContactName,  $Address, $City,  $PostalCode, $Country,  $_GET['CustomerID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE CustomerID = ?');
    $stmt->execute([$_GET['CustomerID']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$customer) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update customer #<?=$customer['CustomerID']?></h2>
    <form action="../Customer/update.php?CustomerID=<?=$customer['CustomerID']?>" method="post">
        <label for="CustomerID">CustomerID </label>
        <label for="CustomerName">Customer Name</label>
        <input type="text" name="CustomerID" placeholder="1" value="<?=$customer['CustomerID']?>" CustomerID ="CustomerID ">
        <input type="text" name="CustomerName" placeholder="" value="<?=$customer['CustomerName']?>" CustomerID ="CustomerName">

        <label for="ContactName">ContactName</label>
        <label for="Address">Address</label>

        <input type="text" name="ContactName" placeholder="" value="<?=$customer['ContactName']?>" CustomerID ="ContactName">
        <input type="text" name="Address" placeholder="" value="<?=$customer['Address']?>" CustomerID ="Address">

        <label for="City">City</label>
        <label for="PostalCode">PostalCode</label>

        <input type="text" name="City" placeholder="" value="<?=$customer['City']?>" CustomerID ="City">
        <input type="text" name="PostalCode" placeholder="" value="<?=$customer['PostalCode']?>" CustomerID ="PostalCode">


        <label for="Country">Country</label>
        <label for="Country"></label>

        <input type="text" name="Country" placeholder="" value="<?=$customer['Country']?>" CustomerID ="Country">

        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Customer/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>