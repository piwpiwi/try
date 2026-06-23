<?php
include_once("conn.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pia Electronics Shop Inventory</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #d5e2d57d;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(90deg, #063609, #0c5a11);
            color: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            padding: 15px 5%;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .title-section h1 {
            font-size: 30px;
            margin: 0;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .title-section p {
            margin: 0;
            margin-top: 3px;
            font-size: 17px;
            color: #d8e4d0;
            font-style: italic;
        }

        .add {
            background-color: #b7da6d;
            color: #063609;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            box-shadow: 0 3px 6px rgba(0,0,0,0.2);
            transition: 0.2s ;
        }

        .add:hover {
            background-color: #d4ec8e;
            transform: scale(1.05);
        }

        h2 {
            width: 90%;
            margin: 40px auto 15px;
            color: #005406;
            font-size: 18px;
            letter-spacing: 0.5px;
            padding-left: 10px;
        }

        table {
            width: 90%;
            margin: 0 auto 40px;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #ccddceff;
            color: #063609;
            border-bottom: 1px solid #6aa772;
            padding: 12px;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .pic {
            width: 120px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }

        .action {
            Width: 15px;
            margin: 20px;
            border: none;
            text-decoration: none;
            background: none;
            transition: 0.2s;
        }

        .action:hover {
            transform: scale(1.18);
        }
    </style>
</head>

<body>
<header>
    <div class="header-container">
        <div class="title-section">
            <h1>Pia Electronics Shop</h1>
            <p>Inventory Records</p>
        </div>
        <a href="index.html" class="add">+ Add Product</a>
    </div>
</header>

<?php
$sql = $conn->prepare("
    SELECT p.*, c.cat_name 
    FROM products_tbl p
    INNER JOIN categories_tbl c ON p.cat_id = c.cat_id
    ORDER BY c.cat_name ASC
");
$sql->execute();

$currentCategory = null ;

while($data = $sql->fetch()){
    $cat_name = $data['cat_name'];
    $prod_name = $data['prod_name'];
    $prod_desc = $data['prod_desc'];
    $price = $data['price'];
    $stock = $data['stock'];
    $pic = $data['pic'];
    $id = $data['prod_id'];

    if ($currentCategory != $cat_name) {
            if ($currentCategory != null) {
                echo "</table>";
            }

    $count_sql = $conn->prepare("
        SELECT COUNT(*) AS total 
        FROM products_tbl p
        INNER JOIN categories_tbl c ON p.cat_id = c.cat_id
        WHERE c.cat_name = ?
    ");
    $count_sql->execute([$cat_name]);
    $count_result = $count_sql->fetch();
    $total = $count_result['total'];

    echo "<h2>$cat_name ($total) </h2>";
    echo "<table>
            <tr>
                <th></th>
                <th>Product</th>
                <th>Description</th>
                <th>Price (₱)</th>
                <th>Stock</th>
                <th></th>
            </tr>";

    $currentCategory = $cat_name;
    }

    echo "<tr>
            <td><img class='pic' src='pics/$pic' alt='Product image'></td>
            <td><strong>$prod_name</strong></td>
            <td>$prod_desc</td>
            <td>₱" . number_format($price, 2) . "</td>
            <td>$stock</td>
            <td><a href='edit.php?id=$id' ><img class='action' src='pencil.png' alt='Update'></a>  
                <a href='delete.php?id=$id' onclick=\"return confirm('Are you sure you want to delete this product?');\">
                <img class='action' src='trash.png' alt='Delete'></a></td>
          </tr>";
}

if ($currentCategory != null) {
    echo "</table>";
}
?>
</body>
</html>
