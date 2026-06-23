<?php
include_once("conn.php");

$id = $_GET['id'];

$sql = $conn->prepare("DELETE FROM products_tbl WHERE prod_id = :prod_id");
$sql->bindParam(':prod_id', $id);
$sql->execute();

echo "<script>alert('Successfully Deleted!')</script>";
echo "<script>window.open('viewdata.php','_self')</script>";
?>