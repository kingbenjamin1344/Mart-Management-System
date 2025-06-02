
<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $CategoryID = isset($_POST['CategoryID']) && !empty($_POST['CategoryID']) && $_POST['CategoryID'] != 'auto' ? $_POST['CategoryID'] : NULL;
    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $CategoryName = isset($_POST['CategoryName']) ? $_POST['CategoryName'] : '';
    $Description = isset($_POST['Description']) ? $_POST['Description'] : '';
   
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO categories VALUES (?, ?, ?)');
    $stmt->execute([$CategoryID, $CategoryName, $Description]);
    // Output message
    $msg = 'Created Successfully!';
}

?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Categories</h2>
    <form action="../Categories/create.php" method="post">
        <label for="CategoryID">Category ID</label>
        <label for="Category Name">Category Name</label>
        <input type="text" name="CategoryID" placeholder="" value="auto" CategoryID="CategoryID">
        <input type="text" name="CategoryName" placeholder="" CategoryID="CategoryName">
        <label for="Description">Description</label>
        <label for="phone"></label>
        <input type="text" name="Description" placeholder="" CategoryID="Description">
    
        
            <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Categories/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>

