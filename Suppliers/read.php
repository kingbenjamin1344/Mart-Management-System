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
$country_filter = isset($_GET['country']) ? $_GET['country'] : '';
$city_filter = isset($_GET['city']) ? $_GET['city'] : '';
$suppliername_filter = isset($_GET['suppliername']) ? $_GET['suppliername'] : '';


// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = 'SELECT * FROM suppliers 
        WHERE SupplierName LIKE :search ';

// Add country filter if provided
if (!empty($country_filter)) {
    $sql .= 'AND Country = :country ';
}
// Add city filter if provided
if (!empty($city_filter)) {
    $sql .= 'AND City = :city ';
}
// Add city filter if provided
if (!empty($suppliername_filter)) {
    $sql .= 'AND SupplierName = :suppliername ';
}



$sql .= 'ORDER BY SupplierID 
          LIMIT :current_page, :record_per_page';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);

// Bind country filter value if provided
if (!empty($country_filter)) {
    $stmt->bindValue(':country', $country_filter, PDO::PARAM_STR);
}
// Bind city filter value if provided
if (!empty($city_filter)) {
    $stmt->bindValue(':city', $city_filter, PDO::PARAM_STR);
}

// Bind city filter value if provided
if (!empty($suppliername_filter)) {
    $stmt->bindValue(':suppliername', $suppliername_filter, PDO::PARAM_STR);
}



$stmt->execute();

// Fetch the records so we can display them in our template.
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_suppliers = $pdo->query('SELECT COUNT(*) FROM suppliers')->fetchColumn();
?>


<?=template_header('Read')?>

<div class="content read">
	<h2>Read Suppliers Details</h2>
	<a href="../Suppliers/create.php" class="create-suppliers">Add Customer</a>

     <!-- Search Bar -->
     <form action="" method="get">
        <input type="text" name="search" placeholder="Search by Shipper Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search">
    </form>

    
    <form action="" method="get">
    <select name="country">
        <option value="">Filter by Country</option>
        <?php
        $countries = $pdo->query('SELECT DISTINCT Country FROM suppliers')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($countries as $country) {
            echo '<option value="' . $country . '">' . $country . '</option>';
        }
        ?>
    </select>
    <select name="city">
        <option value="">Filter by City</option>
        <?php
        $cities = $pdo->query('SELECT DISTINCT City FROM suppliers')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($cities as $city) {
            echo '<option value="' . $city . '">' . $city . '</option>';
        }
        ?>
    </select>

    <select name="suppliername">
        <option value="">Filter by Supplier Names</option>
        <?php
        $suppliernames = $pdo->query('SELECT DISTINCT SupplierName FROM suppliers')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($suppliernames as $suppliername) {
            echo '<option value="' . $suppliername . '">' . $suppliername . '</option>';
        }
        ?>
    </select>



    <input type="submit" i class="fa fa-filter" value="Apply Filter" >
    </form>

            
          <p><p>

	<table>
        <thead>
            <tr>
                
                <td>SupplierID</td>
                <td>SupplierName</td>
                <td>ContactName</td>
                <td>Address</td>
                <td>City</td>
                <td>PostalCode</td>
                <td>Country</td>
                <td>Phone</td>
               
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($suppliers as $suppliers): ?>
            <tr>
                <td><?=$suppliers['SupplierID']?></td>
                <td><?=$suppliers['SupplierName']?></td>
                <td><?=$suppliers['ContactName']?></td>
                <td><?=$suppliers['Address']?></td>
                <td><?=$suppliers['City']?></td>
                <td><?=$suppliers['PostalCode']?></td>
                <td><?=$suppliers['Country']?></td>
                <td><?=$suppliers['Phone']?></td>

                <td class="actions">
                    <a href="../Suppliers/update.php?SupplierID=<?=$suppliers['SupplierID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Suppliers/delete.php?SupplierID=<?=$suppliers['SupplierID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="../Suppliers/read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_suppliers): ?>
		<a href="../Suppliers/read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>