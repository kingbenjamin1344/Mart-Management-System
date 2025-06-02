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
$shippername_filter = isset($_GET['shippername']) ? $_GET['shippername'] : '';


// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = 'SELECT * FROM shippers 
        WHERE ShipperName LIKE :search ';

// Add country filter if provided
if (!empty($shippername_filter)) {
    $sql .= 'AND ShipperName = :shippername ';
}




$sql .= 'ORDER BY ShipperID 
          LIMIT :current_page, :record_per_page';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);

// Bind country filter value if provided
if (!empty($shippername_filter)) {
    $stmt->bindValue(':shippername', $shippername_filter, PDO::PARAM_STR);
}


$stmt->execute();

// Fetch the records so we can display them in our template.
$shippers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_shippers = $pdo->query('SELECT COUNT(*) FROM shippers')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
    <h2>Read Shippers Details </h2>
    <a href="../Shippers/create.php" class="create-shippers">Add Shippers</a>
    
    <!-- Search Bar -->
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search by Shipper Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search">
    </form>

    <form action="" method="get">
    <select name="shippername">
        <option value="">Filter by Shipper Name</option>
        <?php
        $shippernames = $pdo->query('SELECT DISTINCT ShipperName FROM shippers')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($shippernames as $shippername) {
            echo '<option value="' . $shippername . '">' . $shippername . '</option>';
        }
        ?>
    </select>
   
    <input type="submit" i class="fa fa-filter" value="Apply Filter" >
</form>
<p><p>
    <table>
        <thead>
            <tr>
                <td>ShipperID</td>
                <td>ShipperName</td>
                <td>Phone</td>
           
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shippers as $shippers): ?>
            <tr>
                <td><?=$shippers['ShipperID']?></td>
                <td><?=$shippers['ShipperName']?></td>
                <td><?=$shippers['Phone']?></td>
  
                <td class="actions">
                    <a href="../Shippers/update.php?ShipperID=<?=$shippers['ShipperID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Shippers/delete.php?ShipperID=<?=$shippers['ShipperID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="../Shippers/read.php?page=<?=$page-1?>&search=<?=$search?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page * $records_per_page < $num_shippers): ?>
        <a href="../Shippers/read.php?page=<?=$page+1?>&search=<?=$search?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
