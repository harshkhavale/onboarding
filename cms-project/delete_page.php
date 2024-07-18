<?php
require_once('includes/connection.php');
?>
<?php
require_once("includes/functions.php");
?>
<?php
if (intval($_GET['page']) == 0) {
    redirect_to("content.php");
}
$id = $_GET['page'];
if ($subject = get_page_by_id($id,$conn)) {


    $query = "DELETE FROM pages WHERE id = {$id}";
    $result = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) == 1) {
        redirect_to("content.php");
    } else {
        echo "<p> page deletion failed!</p>";
        echo "<p>" . mysqli_error($conn) . "</p>";
        echo "<a href=\"content.php\"> Return to Main Page</p>";
    }
} else {
    redirect_to("content.php");
}
?>
<?php
include "includes/footer.php";
?>