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

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM categories 
                      WHERE CategoryName LIKE :search 
     
                      ORDER BY CategoryID 
                      LIMIT :current_page, :record_per_page');
$stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
$stmt->bindValue(':current_page', ($page - 1) * $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records so we can display them in our template.
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_categories = $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
	<h2>Read Categories Details</h2>
	<a href="../Categories/create.php" class="create-categories">Add Categories</a>

<!-- Search Bar -->
<form action="" method="get">
        <input type="text" name="search" placeholder="Search by Category Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search" >   
    </form>
    <p><p>
	<table>
        <thead>
            <tr>
                <td>CategoryID</td>
                <td>Category Name</td>
                <td>Description</td>
               
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $categories): ?>
            <tr>
                <td><?=$categories['CategoryID']?></td>
                <td><?=$categories['CategoryName']?></td>
                <td><?=$categories['Description']?></td>

                <td class="actions">
                    <a href="../Categories/update.php?CategoryID=<?=$categories['CategoryID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Categories/delete.php?CategoryID=<?=$categories['CategoryID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="../Categories/read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_categories): ?>
		<a href="../Categories/read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>