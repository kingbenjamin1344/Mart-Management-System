<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (isset($_GET['EmployeeID'])) {
    if (!empty($_POST)) {
        $EmployeeID = isset($_POST['EmployeeID']) ? $_POST['EmployeeID'] : NULL;
        $LastName = isset($_POST['LastName']) ? $_POST['LastName'] : '';
        $FirstName = isset($_POST['FirstName']) ? $_POST['FirstName'] : '';
        $BirthDate = isset($_POST['BirthDate']) ? $_POST['BirthDate'] : '';
        $Notes = isset($_POST['Notes']) ? $_POST['Notes'] : '';

        $Photo = isset($_FILES['Photo']) && $_FILES['Photo']['error'] == UPLOAD_ERR_OK ? $_FILES['Photo'] : null;
        if ($Photo) {
            $photoPath = 'uploads/' . basename($Photo['name']);
            move_uploaded_file($Photo['tmp_name'], $photoPath);
        } else {
            $stmt = $pdo->prepare('SELECT Photo FROM employees WHERE EmployeeID = ?');
            $stmt->execute([$_GET['EmployeeID']]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            $photoPath = $employee['Photo'];
        }

        $stmt = $pdo->prepare('UPDATE employees SET EmployeeID = ?, LastName = ?, FirstName = ?, BirthDate = ?, Photo = ?, Notes = ? WHERE EmployeeID = ?');
        $stmt->execute([$EmployeeID, $LastName, $FirstName, $BirthDate, $photoPath, $Notes, $_GET['EmployeeID']]);
        $msg = 'Updated Successfully!';
    }

    $stmt = $pdo->prepare('SELECT * FROM employees WHERE EmployeeID = ?');
    $stmt->execute([$_GET['EmployeeID']]);
    $employees = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employees) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

<div class="content update">
    <h2>Update Employees #<?=$employees['EmployeeID']?></h2>
    <form action="../Employees/update.php?EmployeeID=<?=$employees['EmployeeID']?>" method="post" enctype="multipart/form-data">
        <label for="EmployeeID">EmployeeID</label>
        <label for="LastName">LastName</label>
        <input type="text" name="EmployeeID" placeholder="1" value="<?=$employees['EmployeeID']?>" EmployeeID="EmployeeID">
        <input type="text" name="LastName" placeholder="" value="<?=$employees['LastName']?>" EmployeeID="LastName">

        <label for="FirstName">FirstName</label>
        <label for="BirthDate">BirthDate</label>
        <input type="text" name="FirstName" placeholder="" value="<?=$employees['FirstName']?>" EmployeeID="FirstName">
        <input type="date" name="BirthDate" placeholder="" value="<?=$employees['BirthDate']?>" EmployeeID="BirthDate">

        <label for="Photo">Photo</label>
        <label for="Notes">Notes</label>
        <input type="file" name="Photo" EmployeeID="Photo">
        <input type="text" name="Notes" placeholder="" value="<?=$employees['Notes']?>" EmployeeID="Notes">

        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Employees/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>
