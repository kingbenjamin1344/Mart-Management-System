<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['CustomerID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM customer WHERE CustomerID = ?');
    $stmt->execute([$_GET['CustomerID']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$customer) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM customer WHERE CustomerID = ?');
            $stmt->execute([$_GET['CustomerID']]);
            $msg = 'You have deleted this customer!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Customer/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Customer #<?=$customer['CustomerID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this Customer #<?=$customer['CustomerID']?>?</p>
    <div class="yesno">
        <a href="../Customer/delete.php?CustomerID=<?=$customer['CustomerID']?>&confirm=yes">Yes</a>
        <a href="../Customer/delete.php?CustomerID=<?=$customer['CustomerID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Customer/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>