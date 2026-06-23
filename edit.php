<?php
include_once("conn.php");

$id = $_GET['id'];

$sql = $conn->prepare("
    SELECT p.*, c.cat_name 
    FROM products_tbl p
    INNER JOIN categories_tbl c ON p.cat_id = c.cat_id
    WHERE p.prod_id = :prod_id
");
$sql->bindParam(':prod_id', $id);
$sql->execute();

        while($data = $sql->fetch()){
            $cat_id = $data['cat_id'];
            $cat_name = $data['cat_name'];
            $prod_name = $data['prod_name'];
            $prod_desc = $data['prod_desc'];
            $price = $data['price'];
            $stock = $data['stock'];
            $pic = $data['pic'];
        }
    if (isset($_POST['update'])) {
        $cat_name = $_POST['cat_name'];
        $prod_name = $_POST['prod_name'];
        $prod_desc = $_POST['prod_desc'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $pic = $_POST['pic'];
        
        // Get category id 
        $cat_sql = $conn->prepare("SELECT cat_id FROM categories_tbl WHERE cat_name = :cat_name");
        $cat_sql->bindParam(':cat_name', $cat_name);
        $cat_sql->execute();
       
        $cat = $cat_sql->fetch();
        if ($cat) {
            $cat_id = $cat['cat_id'];
        } else {
            // create new category if not existing
            $insert_cat = $conn->prepare("INSERT INTO categories_tbl (cat_name) VALUES (:cat_name)");
            $insert_cat->bindParam(':cat_name', $cat_name);
            $insert_cat->execute();
            $cat_id = $conn->lastInsertId();
        }

    $query = $conn->prepare("UPDATE products_tbl SET cat_id = :cat_id, prod_name = :prod_name, prod_desc = :prod_desc, 
        price = :price, stock = :stock, pic = :pic WHERE prod_id = :prod_id
    ");
    $query->bindParam(':cat_id', $cat_id);
    $query->bindParam(':prod_name', $prod_name);
    $query->bindParam(':prod_desc', $prod_desc);
    $query->bindParam(':price', $price);
    $query->bindParam(':stock', $stock);
    $query->bindParam(':pic', $pic);
    $query->bindParam(':prod_id', $id);
    $query->execute();

        echo "<script>alert('Successfully Updated!')</script>";
        echo "<script>window.open('viewdata.php','_self')</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pia Electronics Shop Product Update</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #c9dac97d;
            margin: 0;
            padding: 0;
        }
        header {
            background: linear-gradient(90deg, #063609, #106f16ff);
            color: white;
            text-align: center;
            padding: 12px 0;
            font-family: "Segoe UI", Arial, sans-serif;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }

        header h1 {
            font-size: 26px;
            margin: 0;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        header p {
            margin: 2px 0 0 0;
            font-size: 16px;
            font-style: italic;
            color: #d8e4d0;
        }
        form {
            background-color: white;
            width: 450px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 25px 35px;
        }
        h2 {
            text-align: center;
            color: #063609;
            font-size: 20px;
            margin-bottom: 15px;
            margin-top: 5px;
        }
        table {
            width: 100%;
        }
        td {
            padding: 8px;
            vertical-align: middle;
        }
        label {
            font-weight: 600;
            color: #063609;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #b7da6d;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="submit"] {
            margin-top: 15px;
            width: 100%;
            background: #149a1d7d;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background-color: #0c8313;
        }
        .pic {
            text-align: center;
            margin: 10px 0;
        }
        .pic img {
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .back {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #063609;
            text-decoration: none;
            font-size: 13px;
        }
        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Pia Electronics Shop</h1>
    <p>Product Update</p>
</header>

<form action="" method="post">
    <h2>Update Product Details</h2>

    <div class="pic">
        <img src="pics/<?php echo $pic; ?>" alt="Prod img">
    </div>

    <table>
        <tr>
            <td><label>Category:</label></td>
            <td><input type="text" name="cat_name" value="<?php echo $cat_name; ?>"></td>
        </tr>
        <tr>
            <td><label>Product Name:</label></td>
            <td><input type="text" name="prod_name" value="<?php echo $prod_name; ?>"></td>
        </tr>
        <tr>
            <td><label>Description:</label></td>
            <td><input type="text" name="prod_desc" value="<?php echo $prod_desc; ?>"></td>
        </tr>
        <tr>
            <td><label>Price (₱):</label></td>
            <td><input type="text" name="price" value="<?php echo $price; ?>"></td>
        </tr>
        <tr>
            <td><label>Stock:</label></td>
            <td><input type="text" name="stock" value="<?php echo $stock; ?>"></td>
        </tr>
        <tr>
            <td><label>Photo:</label></td>
            <td><input type="file" name="pic" accept="image/*"><?php echo $pic; ?></td>
        </tr>
    </table>

    <input type="submit" name="update" value="Save Changes">
    <a href="viewdata.php" class="back">← Back to Records</a>
</form>

</body>
</html>
