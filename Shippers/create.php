
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $ShipperID = isset($_POST['ShipperID']) && !empty($_POST['ShipperID']) && $_POST['ShipperID'] != 'auto' ? $_POST['ShipperID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $ShipperName = isset($_POST['ShipperName']) ? $_POST['ShipperName'] : '';
    $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';


    
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO shippers VALUES (?, ?, ?)');
    $stmt->execute([$ShipperID, $ShipperName, $Phone]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Shippers</h2>
    <form action="../Shippers/create.php" method="post">
        <label for="ShipperID">Shipper ID</label>
        <label for="ShipperName">Shipper Name</label>
        <input type="text" name="ShipperID" placeholder="" value="auto" ShipperID="ShipperID">
        <input type="text" name="ShipperName" placeholder=" " ShipperID="ShipperName">
        <label for="Phone">Phone</label>
        <label for="CategoryID"></label>
        <input type="text" name="Phone" placeholder="" ShipperID="Phone">



        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Shippers/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

