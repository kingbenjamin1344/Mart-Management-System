
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $SupplierID = isset($_POST['SupplierID']) && !empty($_POST['SupplierID']) && $_POST['SupplierID'] != 'auto' ? $_POST['SupplierID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $SupplierName = isset($_POST['SupplierName']) ? $_POST['SupplierName'] : '';
    $ContactName = isset($_POST['ContactName']) ? $_POST['ContactName'] : '';
    $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
    $City = isset($_POST['City']) ? $_POST['City'] : '';
    $PostalCode = isset($_POST['PostalCode']) ? $_POST['PostalCode'] : '';
    $Country = isset($_POST['Country']) ? $_POST['Country'] : '';
    $Phone = isset($_POST['Phone']) ? $_POST['Phone'] : '';
    
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO suppliers VALUES (?, ?, ?, ?, ?, ?,?,?)');
    $stmt->execute([$SupplierID, $SupplierName, $ContactName, $Address, $City, $PostalCode, $Country, $Phone ]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Suppliers</h2>
    <form action="../Suppliers/create.php" method="post">
        <label for="SupplierID">Supplier ID</label>
        <label for="SupplierName">Supplier Name</label>
        <input type="text" name="SupplierID" placeholder="" value="auto" SupplierID="SupplierID">
        <input type="text" name="SupplierName" placeholder=" " SupplierID="SupplierName">
        <label for="ContactName">Contact Name</label>
        <label for="Address">Address</label>
        <input type="text" name="ContactName" placeholder="" SupplierID="ContactName">
        <input type="text" name="Address" placeholder="" SupplierID="Address">
        <label for="City">City</label>
        <label for="PostalCode">PostalCode</label>
        <input type="text" name="City" placeholder=" " SupplierID="City">
        <input type="text" name="PostalCode" placeholder=" " SupplierID="PostalCode">
        <label for="Country">Country</label>
        <label for="Phone">Phone</label>
        <input type="text" name="Country" placeholder=" " SupplierID="Country">
        <input type="text" name="Phone" placeholder=" " SupplierID="Phone">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Suppliers/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

