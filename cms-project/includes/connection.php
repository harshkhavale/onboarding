<?php
require("constants.php");
$conn = mysqli_connect(SERVER, USER, PASSWORD, DB_NAME);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>