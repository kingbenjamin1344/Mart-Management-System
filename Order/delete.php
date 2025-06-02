<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['OrderID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE OrderID = ?');
    $stmt->execute([$_GET['OrderID']]);
    $orders = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$orders) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM orders WHERE OrderID = ?');
            $stmt->execute([$_GET['OrderID']]);
            $msg = 'You have deleted this Orders!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Order/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete orders #<?=$orders['OrderID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this employees #<?=$orders['OrderID']?>?</p>
    <div class="yesno">
        <a href="../Order/delete.php?OrderID=<?=$orders['OrderID']?>&confirm=yes">Yes</a>
        <a href="../Order/delete.php?OrderID=<?=$orders['OrderID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Order/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>