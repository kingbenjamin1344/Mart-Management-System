<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['ShipperID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM shippers WHERE ShipperID = ?');
    $stmt->execute([$_GET['ShipperID']]);
    $shippers = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$shippers) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM shippers WHERE ShipperID = ?');
            $stmt->execute([$_GET['ShipperID']]);
            $msg = 'You have deleted this Shippers!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Shippers/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete shippers #<?=$shippers['ShipperID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this Shippers #<?=$shippers['ShipperID']?>?</p>
    <div class="yesno">
        <a href="../Shippers/delete.php?ShipperID=<?=$shippers['ShipperID']?>&confirm=yes">Yes</a>
        <a href="../Shippers/delete.php?ShipperID=<?=$shippers['ShipperID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Shippers/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>