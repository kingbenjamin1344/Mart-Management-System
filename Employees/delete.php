<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['EmployeeID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE EmployeeID = ?');
    $stmt->execute([$_GET['EmployeeID']]);
    $employees = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employees) {
        exit('Contact doesn\'t exist with that ID!');
    }
    // Make sure the user confirms beore deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM employees WHERE EmployeeID = ?');
            $stmt->execute([$_GET['EmployeeID']]);
            $msg = 'You have deleted this Employee!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: ../Employees/read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}

?>

<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete employees #<?=$employees['EmployeeID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete this employees #<?=$employees['EmployeeID']?>?</p>
    <div class="yesno">
        <a href="../Employees/delete.php?EmployeeID=<?=$employees['EmployeeID']?>&confirm=yes">Yes</a>
        <a href="../Employees/delete.php?EmployeeID=<?=$employees['EmployeeID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
    <a href="../Employees/read.php" onclick="window.history.back();">Back</a>
</div>


<?=template_footer()?>