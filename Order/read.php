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
$orderdate_filter = isset($_GET['orderdate']) ? $_GET['orderdate'] : '';
$shippername_filter = isset($_GET['shippername']) ? $_GET['shippername'] : '';


// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = 'SELECT * FROM orders 
        WHERE CustomerID LIKE :search ';

// Add country filter if provided
if (!empty($orderdate_filter)) {
    $sql .= 'AND OrderDate = :orderdate ';
}
// Add city filter if provided
if (!empty($shippername_filter)) {
    $sql .= 'AND ShipperID = :shippername ';
}



$sql .= 'ORDER BY OrderID 
          LIMIT :current_page, :record_per_page';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);

// Bind country filter value if provided
if (!empty($orderdate_filter)) {
    $stmt->bindValue(':orderdate', $orderdate_filter, PDO::PARAM_STR);
}
// Bind city filter value if provided
if (!empty($shippername_filter)) {
    $stmt->bindValue(':shippername', $shippername_filter, PDO::PARAM_STR);
}


$stmt->execute();

// Fetch the records so we can display them in our template.
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_orders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
?>


<?=template_header('Read')?>

<div class="content read">
	<h2>Read Order</h2>
	<a href="../Order/create.php" class="create-orders">Add Orders</a>

    
    <form action="" method="get">
    <select name="orderdate">
        <option value="">Filter by Order Date</option>
        <?php
        $orderdates = $pdo->query('SELECT DISTINCT OrderDate FROM orders')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($orderdates as $orderdate) {
            echo '<option value="' . $orderdate . '">' . $orderdate . '</option>';
        }
        ?>
    </select>
    <select name="shippername">
        <option value="">Filter by Shipper Name</option>
        <?php
        $shippernames = $pdo->query('SELECT DISTINCT ShipperID FROM orders')->fetchAll(PDO::FETCH_COLUMN);
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
                <td>Order ID </td>
                <td>CustomerID</td>
                <td>EmployeeID</td>
                <td>OrderDate</td>
                <td>ShipperID</td>
         

               
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $orders): ?>
            <tr>
                <td><?=$orders['OrderID']?></td>
                <td><?=$orders['CustomerID']?></td>
                <td><?=$orders['EmployeeID']?></td>
                <td><?=$orders['OrderDate']?></td>
                <td><?=$orders['ShipperID']?></td>



                <td class="actions">
                    <a href="../Order/update.php?OrderID=<?=$orders['OrderID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Order/delete.php?OrderID=<?=$orders['OrderID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="../Order/read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_orders): ?>
		<a href="../Order/read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>