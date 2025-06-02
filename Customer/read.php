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
$postalcode_filter = isset($_GET['postalcode']) ? $_GET['postalcode'] : '';

// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$sql = 'SELECT * FROM customer 
        WHERE CustomerName LIKE :search ';

// Add country filter if provided
if (!empty($country_filter)) {
    $sql .= 'AND Country = :country ';
}
// Add city filter if provided
if (!empty($city_filter)) {
    $sql .= 'AND City = :city ';
}
// Add city filter if provided
if (!empty($postalcode_filter)) {
    $sql .= 'AND PostalCode = :postalcode ';
}



$sql .= 'ORDER BY CustomerID 
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
if (!empty($postalcode_filter)) {
    $stmt->bindValue(':postalcode', $postalcode_filter, PDO::PARAM_STR);
}


$stmt->execute();

// Fetch the records so we can display them in our template.
$customer = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_customer = $pdo->query('SELECT COUNT(*) FROM customer')->fetchColumn();
?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Read Customer Details</h2>
    <a href="../Customer/create.php" class="create-customer">Add Customer</a>

    <!-- Search Bar -->
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search by Customer Name" value="<?=htmlspecialchars($search, ENT_QUOTES)?>">
        <input type="submit" value="Search">
    </form> 

    <form action="" method="get">
    <select name="country">
        <option value="">Filter by Country</option>
        <?php
        $countries = $pdo->query('SELECT DISTINCT Country FROM customer')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($countries as $country) {
            echo '<option value="' . $country . '">' . $country . '</option>';
        }
        ?>
    </select>
    <select name="city">
        <option value="">Filter by City</option>
        <?php
        $cities = $pdo->query('SELECT DISTINCT City FROM customer')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($cities as $city) {
            echo '<option value="' . $city . '">' . $city . '</option>';
        }
        ?>
    </select>
    <select name="postalcode">
        <option value="">Filter by Postalcode</option>
        <?php
        $postalcodes = $pdo->query('SELECT DISTINCT PostalCode FROM customer')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($postalcodes as $postalcode) {
            echo '<option value="' . $postalcode . '">' . $postalcode . '</option>';
        }
        ?>
    </select>
    <input type="submit" i class="fa fa-filter" value="Apply Filter" >
</form>
<p><p>
    <table>
        <thead>
            <tr>
                <td>CustomerID</td>
                <td>CustomerName</td>
                <td>ContactName</td>
                <td>Address</td>
                <td>City</td>
                <td>PostalCode</td>
                <td>Country</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customer as $customer): ?>
            <tr>
                <td><?=$customer['CustomerID']?></td>
                <td><?=$customer['CustomerName']?></td>
                <td><?=$customer['ContactName']?></td>
                <td><?=$customer['Address']?></td>
                <td><?=$customer['City']?></td>
                <td><?=$customer['PostalCode']?></td>
                <td><?=$customer['Country']?></td>

                <td class="actions">
                    <a href="../Customer/update.php?CustomerID=<?=$customer['CustomerID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="../Customer/delete.php?CustomerID=<?=$customer['CustomerID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="../Customer/read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page*$records_per_page < $num_customer): ?>
        <a href="../Customer/read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>
