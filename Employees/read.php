<?php
include 'function.php';

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Get the search keyword if provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = 10;

// Get the country filter if provided
$lastname_filter = isset($_GET['lastname']) ? $_GET['lastname'] : '';
$firstname_filter = isset($_GET['firstname']) ? $_GET['firstname'] : '';
$birthdate_filter = isset($_GET['birthdate']) ? $_GET['birthdate'] : '';

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = 'SELECT * FROM employees 
        WHERE LastName LIKE :search ';

// Add country filter if provided
if (!empty($lastname_filter)) {
    $sql .= 'AND LastName = :lastname ';
}
// Add city filter if provided
if (!empty($firstname_filter)) {
    $sql .= 'AND FirstName = :firstname ';
}
// Add city filter if provided
if (!empty($birthdate_filter)) {
    $sql .= 'AND BirthDate = :birthdate ';
}

$sql .= 'ORDER BY EmployeeID 
          LIMIT :current_page, :record_per_page';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);

// Bind country filter value if provided
if (!empty($lastname_filter)) {
    $stmt->bindValue(':lastname', $lastname_filter, PDO::PARAM_STR);
}
// Bind city filter value if provided
if (!empty($firstname_filter)) {
    $stmt->bindValue(':firstname', $firstname_filter, PDO::PARAM_STR);
}
if (!empty($birthdate_filter)) {
    $stmt->bindValue(':birthdate', $birthdate_filter, PDO::PARAM_STR);
}

$stmt->execute();

// Fetch the records so we can display them in our template.
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_employees = $pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Read employees Details</h2>
    <a href="../Employees/create.php" class="create-employees">Add Employee</a>

    <!-- Search Bar -->
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search by Last Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search">
    </form> 

    <form action="" method="get">
        <select name="lastname">
            <option value="">Filter by last name</option>
            <?php
            $lastnames = $pdo->query('SELECT DISTINCT LastName FROM employees')->fetchAll(PDO::FETCH_COLUMN);
            foreach ($lastnames as $lastname) {
                echo '<option value="' . $lastname . '">' . $lastname . '</option>';
            }
            ?>
        </select>
        <select name="firstname">
            <option value="">Filter by firstname</option>
            <?php
            $firstnames = $pdo->query('SELECT DISTINCT FirstName FROM employees')->fetchAll(PDO::FETCH_COLUMN);
            foreach ($firstnames as $firstname) {
                echo '<option value="' . $firstname . '">' . $firstname . '</option>';
            }
            ?>
        </select>
        <select name="birthdate">
            <option value="">Filter by birthdate</option>
            <?php
            $birthdates = $pdo->query('SELECT DISTINCT BirthDate FROM employees')->fetchAll(PDO::FETCH_COLUMN);
            foreach ($birthdates as $birthdate) {
                echo '<option value="' . $birthdate . '">' . $birthdate . '</option>';
            }
            ?>
        </select>
        <input type="submit" i class="fa fa-filter" value="Apply Filter" >
    </form>
    <p><p>
    <table>
        <thead>
            <tr>
                <td>EmployeeID</td>
                <td>LastName</td>
                <td>FirstName</td>
                <td>BirthDate</td>
                <td>Photo</td>
                <td>Notes</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
            <tr>
                <td><?=$employee['EmployeeID']?></td>
                <td><?=$employee['LastName']?></td>
                <td><?=$employee['FirstName']?></td>
                <td><?=$employee['BirthDate']?></td>
                <td>
                    <?php if (!empty($employee['Photo'])): ?>
                        <img src="<?=$employee['Photo']?>" alt="Photo" width="50" height="50">
                    <?php endif; ?>
                </td>
                <td><?=$employee['Notes']?></td>
                <td class="actions">
                    <a href="../Employees/update.php?EmployeeID=<?=$employee['EmployeeID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Employees/delete.php?EmployeeID=<?=$employee['EmployeeID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="../Employees/read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page * $records_per_page < $num_employees): ?>
        <a href="../Employees/read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
