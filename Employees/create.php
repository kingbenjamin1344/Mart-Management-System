<?php
include 'function.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Set-up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
    $EmployeeID = isset($_POST['EmployeeID']) && !empty($_POST['EmployeeID']) && $_POST['EmployeeID'] != 'auto' ? $_POST['EmployeeID'] : NULL;
    $LastName = isset($_POST['LastName']) ? $_POST['LastName'] : '';
    $FirstName = isset($_POST['FirstName']) ? $_POST['FirstName'] : '';
    $BirthDate = isset($_POST['BirthDate']) ? $_POST['BirthDate'] : '';
    $Photo = '';
    $Notes = isset($_POST['Notes']) ? $_POST['Notes'] : '';

    // Check if a file was uploaded
    if (isset($_FILES['Photo']) && $_FILES['Photo']['error'] == UPLOAD_ERR_OK) {
        // Validate the file type (optional)
        $allowed = ['jpg', 'jpeg', 'png', 'gif','jfif'];
        $file_ext = pathinfo($_FILES['Photo']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($file_ext), $allowed)) {
            // Move the uploaded file to the target directory
            $target_dir = 'uploads/';
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $target_file = $target_dir . basename($_FILES['Photo']['name']);
            if (move_uploaded_file($_FILES['Photo']['tmp_name'], $target_file)) {
                $Photo = $target_file;
            } else {
                $msg = 'Failed to upload photo.';
            }
        } else {
            $msg = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
        }
    }

    if (empty($msg)) {
        // Insert new record into the employees table
        $stmt = $pdo->prepare('INSERT INTO employees VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$EmployeeID, $LastName, $FirstName, $BirthDate, $Photo, $Notes]);
        // Output message
        $msg = 'Created Successfully!';
    }
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create Employee</h2>
    <form action="../Employees/create.php" method="post" enctype="multipart/form-data">
        <label for="EmployeeID">ID</label>
        <label for="LastName">Last Name</label>
        <input type="text" name="EmployeeID" placeholder="" value="auto" id="EmployeeID">
        <input type="text" name="LastName" placeholder=" " id="LastName">
        <label for="FirstName">FirstName</label>
        <label for="BirthDate">BirthDate</label>
        <input type="text" name="FirstName" placeholder="" id="FirstName">
        <input type="date" name="BirthDate" placeholder="" id="BirthDate">
        <label for="Photo">Photo</label>
        <label for="Notes">Notes</label>
        <input type="file" name="Photo" id="Photo">
        <input type="text" name="Notes" placeholder=" " id="Notes">

        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <a href="../Employees/read.php" onclick="window.history.back();">Back</a>
</div>

<?=template_footer()?>
