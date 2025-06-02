<?php
include 'function.php';
// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 10;

// Get the minimum, maximum, and exact quantity from GET request
$min_quantity = isset($_GET['min_quantity']) && is_numeric($_GET['min_quantity']) ? (int)$_GET['min_quantity'] : null;
$max_quantity = isset($_GET['max_quantity']) && is_numeric($_GET['max_quantity']) ? (int)$_GET['max_quantity'] : null;
$exact_quantity = isset($_GET['exact_quantity']) && is_numeric($_GET['exact_quantity']) ? (int)$_GET['exact_quantity'] : null;

// Prepare the base SQL statement
$sql = 'SELECT * FROM order_details WHERE 1=1';

// Add conditions for minimum, maximum, and exact quantity
if ($exact_quantity !== null) {
    $sql .= ' AND Quantity = :exact_quantity';
} else {
    if ($min_quantity !== null) {
        $sql .= ' AND Quantity >= :min_quantity';
    }
    if ($max_quantity !== null) {
        $sql .= ' AND Quantity <= :max_quantity';
    }
}

// Add the ORDER BY and LIMIT clauses
$sql .= ' ORDER BY OrderDetailID LIMIT :current_page, :record_per_page';

// Prepare the SQL statement
$stmt = $pdo->prepare($sql);

// Bind the values for minimum, maximum, and exact quantity if they are set
if ($exact_quantity !== null) {
    $stmt->bindValue(':exact_quantity', $exact_quantity, PDO::PARAM_INT);
} else {
    if ($min_quantity !== null) {
        $stmt->bindValue(':min_quantity', $min_quantity, PDO::PARAM_INT);
    }
    if ($max_quantity !== null) {
        $stmt->bindValue(':max_quantity', $max_quantity, PDO::PARAM_INT);
    }
}

// Bind the values for pagination
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of order details
$count_sql = 'SELECT COUNT(*) FROM order_details WHERE 1=1';
if ($exact_quantity !== null) {
    $count_sql .= ' AND Quantity = :exact_quantity';
} else {
    if ($min_quantity !== null) {
        $count_sql .= ' AND Quantity >= :min_quantity';
    }
    if ($max_quantity !== null) {
        $count_sql .= ' AND Quantity <= :max_quantity';
    }
}
$count_stmt = $pdo->prepare($count_sql);
if ($exact_quantity !== null) {
    $count_stmt->bindValue(':exact_quantity', $exact_quantity, PDO::PARAM_INT);
} else {
    if ($min_quantity !== null) {
        $count_stmt->bindValue(':min_quantity', $min_quantity, PDO::PARAM_INT);
    }
    if ($max_quantity !== null) {
        $count_stmt->bindValue(':max_quantity', $max_quantity, PDO::PARAM_INT);
    }
}
$count_stmt->execute();
$num_order_details = $count_stmt->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Read Order Details</h2>
    <a href="../Order_Details/create.php" class="create-order_details">Add Order Details</a>
    
    <form method="get" action="../Order_Details/read.php">
        <label for="min_quantity">Min Quantity:</label>
        <input type="number" id="min_quantity" name="min_quantity" value="<?= htmlspecialchars($min_quantity) ?>">
        <label for="max_quantity">Max Quantity:</label>
        <input type="number" id="max_quantity" name="max_quantity" value="<?= htmlspecialchars($max_quantity) ?>">
        <label for="exact_quantity">Same Quantity:</label>
        <input type="number" id="exact_quantity" name="exact_quantity" value="<?= htmlspecialchars($exact_quantity) ?>">
        <input type="submit" i class="fa fa-filter" value="Filter" >
    </form>
    <p><p>
    <table>
        <thead>
            <tr>
                <td>OrderDetailID</td>
                <td>OrderID</td>
                <td>ProductID</td>
                <td>Quantity</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_details as $order_detail): ?>
            <tr>
                <td><?=$order_detail['OrderDetailID']?></td>
                <td><?=$order_detail['OrderID']?></td>
                <td><?=$order_detail['ProductID']?></td>
                <td><?=$order_detail['Quantity']?></td>
                <td class="actions">
                    <a href="../Order_Details/update.php?OrderDetailID=<?=$order_detail['OrderDetailID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Order_Details/delete.php?OrderDetailID=<?=$order_detail['OrderDetailID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="../Order_Details/read.php?page=<?=$page-1?>&min_quantity=<?=htmlspecialchars($min_quantity)?>&max_quantity=<?=htmlspecialchars($max_quantity)?>&exact_quantity=<?=htmlspecialchars($exact_quantity)?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page * $records_per_page < $num_order_details): ?>
        <a href="../Order_Details/read.php?page=<?=$page+1?>&min_quantity=<?=htmlspecialchars($min_quantity)?>&max_quantity=<?=htmlspecialchars($max_quantity)?>&exact_quantity=<?=htmlspecialchars($exact_quantity)?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
