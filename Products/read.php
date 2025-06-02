<?php
include 'function.php';

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Get the search keyword if provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get the page via GET request (URL param: page), if none exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = 10;

// Get the category and supplier filters if provided
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$supplier_filter = isset($_GET['supplier']) ? $_GET['supplier'] : '';

// Get the sort filter for price
$sort_price = isset($_GET['sort_price']) ? $_GET['sort_price'] : '';

// Prepare the SQL statement and get records from our products table, LIMIT will determine the page
$sql = 'SELECT * FROM products 
        WHERE ProductName LIKE :search ';

// Add category filter if provided
if (!empty($category_filter)) {
    $sql .= 'AND CategoryID = :category ';
}
// Add supplier filter if provided
if (!empty($supplier_filter)) {
    $sql .= 'AND SupplierID = :supplier ';
}

// Add sorting for price if selected
if ($sort_price === 'highest') {
    $sql .= 'ORDER BY Price DESC ';
} elseif ($sort_price === 'lowest') {
    $sql .= 'ORDER BY Price ASC ';
} else {
    $sql .= 'ORDER BY ProductID ';
}

$sql .= 'LIMIT :current_page, :record_per_page';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);

// Bind category filter value if provided
if (!empty($category_filter)) {
    $stmt->bindValue(':category', $category_filter, PDO::PARAM_STR);
}
// Bind supplier filter value if provided
if (!empty($supplier_filter)) {
    $stmt->bindValue(':supplier', $supplier_filter, PDO::PARAM_STR);
}

$stmt->execute();

// Fetch the records so we can display them in our template
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories from Categories table
$categories_stmt = $pdo->query('SELECT * FROM categories');
$categories = $categories_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of products, this is so we can determine whether there should be a next and previous button
$num_products = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Read Products Details</h2>
    <a href="../Products/create.php" class="create-products">Add Products</a>
    
    <!-- Search Bar -->
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search by product Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search">
    </form>

    <form action="" method="get">
        <select name="category">
            <option value="">Filter by category name</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?=$category['CategoryID']?>" <?=($category['CategoryID'] == $category_filter ? 'selected' : '')?>><?=$category['CategoryName']?></option>
            <?php endforeach; ?>
        </select>
  
        <select name="sort_price">
            <option value="">Sort by</option>
            <option value="highest" <?= $sort_price === 'highest' ? 'selected' : '' ?>>Highest Price</option>
            <option value="lowest" <?= $sort_price === 'lowest' ? 'selected' : '' ?>>Lowest Price</option>
        </select>
        <input type="submit" i class="fa fa-filter" value="Apply Filter" >
    </form>
    <p><p>
    <table>
        <thead>
            <tr>
                <td>Product ID</td>
                <td>Product Name</td>
                <td>Category Name</td>
                <td>Description</td>
                <td>Unit</td>
                <td>Price</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($products as $product): ?>
        <?php
        // Fetch category information for this product
        $category_stmt = $pdo->prepare('SELECT CategoryName, Description FROM categories WHERE CategoryID = :category_id');
        $category_stmt->bindValue(':category_id', $product['CategoryID']);
        $category_stmt->execute();
        $category = $category_stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <tr>
            <td><?=$product['ProductID']?></td>
            <td><?=$product['ProductName']?></td>
            <!-- Add Category Name and Description -->
            <td><?=$category['CategoryName']?></td>
            <td><?=$category['Description']?></td>
            <!-- End of Category Name and Description -->
            <td><?=$product['Unit']?></td>
            <td><?=$product['Price']?></td>
            <td class="actions">
                <a href="../Products/update.php?ProductID=<?=$product['ProductID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                <a href="../Products/delete.php?ProductID=<?=$product['ProductID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>

    <!-- Centered Message -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="../Products/howto_js_collapse.php">Go to Collapsible Data</a>
    </div>
    
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="../Products/read.php?page=<?=$page-1?>&search=<?=$search?>&category=<?=$category_filter?>&supplier=<?=$supplier_filter?>&sort_price=<?=$sort_price?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page * $records_per_page < $num_products): ?>
        <a href="../Products/read.php?page=<?=$page+1?>&search=<?=$search?>&category=<?=$category_filter?>&supplier=<?=$supplier_filter?>&sort_price=<?=$sort_price?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
