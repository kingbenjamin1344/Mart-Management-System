<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['SupplierID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM suppliers WHERE SupplierID = ?');
    $stmt->execute([$_GET['SupplierID']]);
    $suppliers = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$suppliers) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM suppliers WHERE SupplierID = ?');
            $stmt->execute([$_GET['SupplierID']]);
            $msg = 'You have deleted this Supplier!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Suppliers/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Customer #<?=$suppliers['SupplierID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this Suppliers #<?=$suppliers['SupplierID']?>?</p>
    <div class="yesno">
        <a href="../Suppliers/delete.php?SupplierID=<?=$suppliers['SupplierID']?>&confirm=yes">Yes</a>
        <a href="../Suppliers/delete.php?SupplierID=<?=$suppliers['SupplierID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Suppliers/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>