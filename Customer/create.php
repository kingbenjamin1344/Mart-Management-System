
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $CustomerID = isset($_POST['CustomerID']) && !empty($_POST['CustomerID']) && $_POST['CustomerID'] != 'auto' ? $_POST['CustomerID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $CustomerName = isset($_POST['CustomerName']) ? $_POST['CustomerName'] : '';
    $ContactName = isset($_POST['ContactName']) ? $_POST['ContactName'] : '';
    $Address = isset($_POST['Address']) ? $_POST['Address'] : '';
    $City = isset($_POST['City']) ? $_POST['City'] : '';
    $PostalCode = isset($_POST['PostalCode']) ? $_POST['PostalCode'] : '';
    $Country = isset($_POST['Country']) ? $_POST['Country'] : '';
    
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO customer VALUES (?, ?, ?, ?, ?, ?,?)');
    $stmt->execute([$CustomerID, $CustomerName, $ContactName, $Address, $City, $PostalCode, $Country ]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Customer</h2>
    <form action="../Customer/create.php" method="post">
        <label for="CustomerID">ID</label>
        <label for="CustomerName">Name</label>
        <input type="text" name="CustomerID" placeholder="" value="auto" CustomerID="CustomerID">
        <input type="text" name="CustomerName" placeholder=" " CustomerID="CustomerName">
        <label for="ContactName">Contact Name</label>
        <label for="Address">Address</label>
        <input type="text" name="ContactName" placeholder="" CustomerID="ContactName">
        <input type="text" name="Address" placeholder="" CustomerID="Address">
        <label for="City">City</label>
        <label for="PostalCode">PostalCode</label>
        <input type="text" name="City" placeholder=" " CustomerID="City">
        <input type="text" name="PostalCode" placeholder=" " CustomerID="PostalCode">
        <label for="Country">Country</label>
        <label for="created"></label>
        <input type="text" name="Country" placeholder=" " CustomerID="Country">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Customer/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

