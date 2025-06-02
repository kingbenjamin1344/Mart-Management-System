<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['CategoryID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE CategoryID = ?');
    $stmt->execute([$_GET['CategoryID']]);
    $categories = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$categories) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM categories WHERE CategoryID = ?');
            $stmt->execute([$_GET['CategoryID']]);
            $msg = 'You have deleted this Categories!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Categories/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Categories #<?=$categories['CategoryID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this Categories #<?=$categories['CategoryID']?>?</p>
    <div class="yesno">
        <a href="../Categories/delete.php?CategoryID=<?=$categories['CategoryID']?>&confirm=yes">Yes</a>
        <a href="../Categories/delete.php?CategoryID=<?=$categories['CategoryID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Categories/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>