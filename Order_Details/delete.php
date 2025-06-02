<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['OrderDetailID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM order_details WHERE OrderDetailID = ?');
    $stmt->execute([$_GET['OrderDetailID']]);
    $order_details = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order_details) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM order_details WHERE OrderDetailID = ?');
            $stmt->execute([$_GET['OrderDetailID']]);
            $msg = 'You have deleted this Order Detail!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Order_Details/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Order Detail #<?=$order_details['OrderDetailID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this employees #<?=$order_details['OrderDetailID']?>?</p>
    <div class="yesno">
        <a href="../Order_Details/delete.php?OrderDetailID=<?=$order_details['OrderDetailID']?>&confirm=yes">Yes</a>
        <a href="../Order_Details/delete.php?OrderDetailID=<?=$order_details['OrderDetailID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Order_DetailIs/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>