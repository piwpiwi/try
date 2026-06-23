<?php 
include_once("conn.php");

if(isset($_POST['add'])){
	$cat_name = htmlentities($_POST['cat_name']);
	$prod_name = htmlentities($_POST['prod_name']);
	$prod_desc = htmlentities($_POST['prod_desc']);
	$price = htmlentities($_POST['price']);
	$stock = htmlentities($_POST['stock']);
	
	//image processing
	$imgFile = $_FILES['pic']['name'];
	$imgSize = $_FILES['pic']['size'];
	$imgTempname = $_FILES['pic']['tmp_name'];
	$imgExt = pathinfo($imgFile,PATHINFO_EXTENSION);
	$validExt = array('jpg','png','gif','jpeg');
	
	$newname = rand(1000,100000000).".".$imgExt;   
	$directory = "pics/";
	
	if(in_array($imgExt,$validExt)){
		if($imgSize < 1000000){
			move_uploaded_file($imgTempname,$directory.$newname);

		//checking if catergory already exists
        $sql = $conn->prepare("SELECT cat_id FROM categories_tbl WHERE cat_name = :cat_name");
        $sql->bindParam(':cat_name', $cat_name);
        $sql->execute();
	
        if($sql->rowCount() > 0){
            $row = $sql->fetch();
            $cat_id = $row['cat_id'];
        } else {
            $sql = $conn->prepare("INSERT INTO categories_tbl (cat_name) VALUES (:cat_name)");
            $sql->bindParam(':cat_name', $cat_name);
            $sql->execute();
            $cat_id = $conn->lastInsertId();
        }

        $sql = $conn->prepare("INSERT INTO products_tbl (cat_id, prod_name, prod_desc, price, stock, pic)
                            VALUES (:cat_id, :prod_name, :prod_desc, :price, :stock, :pic)");
        $sql->bindParam(':cat_id', $cat_id);
        $sql->bindParam(':prod_name', $prod_name);
        $sql->bindParam(':prod_desc', $prod_desc);
        $sql->bindParam(':price', $price);
        $sql->bindParam(':stock', $stock);
        $sql->bindParam(':pic', $newname);
        $sql->execute();

			
			
		echo "<script>alert('Profile Successfully Created!')</script>";
		echo "<script>window.open('index.html','_self')</script>";
		} else {
		echo "<script>alert('Sorry, file is too large')</script>";
		echo "<script>window.open('index.html','_self')</script>";
		}
	} else {
		echo "<script>alert('Sorry, only png, jpg, jpeg, gif is allowed!')</script>";
		echo "<script>window.open('index.html','_self')</script>";
	}

}
?>