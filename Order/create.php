
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $OrderID = isset($_POST['OrderID']) && !empty($_POST['OrderID']) && $_POST['OrderID'] != 'auto' ? $_POST['OrderID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $CustomerID = isset($_POST['CustomerID']) ? $_POST['CustomerID'] : '';
    $EmployeeID = isset($_POST['EmployeeID']) ? $_POST['EmployeeID'] : '';
    $OrderDate = isset($_POST['OrderDate']) ? $_POST['OrderDate'] : '';
    $ShipperID = isset($_POST['ShipperID']) ? $_POST['ShipperID'] : '';


    
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO orders VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$OrderID, $CustomerID, $EmployeeID, $OrderDate, $ShipperID ]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Order</h2>
    <form action="../Order/create.php" method="post">
        <label for="OrderID">Order ID</label>
        <label for="CustomerID">Customer ID</label>
        <input type="text" name="OrderID" placeholder="" value="auto" OrderID="OrderID">
        <input type="text" name="CustomerID" placeholder=" " OrderID="CustomerID">
        <label for="EmployeeID">Employee ID</label>
        <label for="OrderDate">Order Date</label>
        <input type="text" name="EmployeeID" placeholder="" OrderID="EmployeeID">
        <input type="date" name="Order Date" placeholder="" OrderID="OrderDate">
        <label for="ShipperID">ShipperID</label>
        <label for=""></label>
        <input type="text" name="ShipperID" placeholder=" " OrderID="ShipperID">
    

        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Order/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

