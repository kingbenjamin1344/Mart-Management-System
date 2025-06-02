<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['ProductID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM products WHERE ProductID = ?');
    $stmt->execute([$_GET['ProductID']]);
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$products) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM products WHERE ProductID = ?');
            $stmt->execute([$_GET['ProductID']]);
            $msg = 'You have deleted this Employee!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Products/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete products #<?=$products['ProductID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this Product #<?=$products['ProductID']?>?</p>
    <div class="yesno">
        <a href="../Products/delete.php?ProductID=<?=$products['ProductID']?>&confirm=yes">Yes</a>
        <a href="../Products/delete.php?ProductID=<?=$products['ProductID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Products/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>