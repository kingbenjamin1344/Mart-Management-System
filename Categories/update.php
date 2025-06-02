


<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['CategoryID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, but instead we update a record and not insert
        $CategoryID = isset($_POST['CategoryID']) ? $_POST['CategoryID'] : NULL;
        $CategoryName = isset($_POST['CategoryName']) ? $_POST['CategoryName'] : '';
        $Description = isset($_POST['Description']) ? $_POST['Description'] : '';
  
        // Update the record
        $stmt = $pdo->prepare('UPDATE categories SET CategoryID = ?, CategoryName = ?, Description = ? WHERE CategoryID = ?');
        $stmt->execute([$CategoryID , $CategoryName, $Description, $_GET['CategoryID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE CategoryID = ?');
    $stmt->execute([$_GET['CategoryID']]);
    $categories = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$categories) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}


?>

<?=template_header('Read')?>

<div class="content update">
	<h2>Update Categories #<?=$categories['CategoryID']?></h2>
    <form action="../Categories/update.php?CategoryID=<?=$categories['CategoryID']?>" method="post">
        <label for="CategoryID">CategoryID </label>
        <label for="CategoryName">Category Name</label>
        <input type="text" name="CategoryID" placeholder="1" value="<?=$categories['CategoryID']?>" CategoryID ="CategoryID ">
        <input type="text" name="CategoryName" placeholder="" value="<?=$categories['CategoryName']?>" CategoryID ="CategoryName">
        <label for="Description">Email</label>
        <label for="phone"></label>
        <input type="text" name="Description" placeholder="" value="<?=$categories['Description']?>" CategoryID ="Description">
        <input type="submit" value="Update">
        
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Categories/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>