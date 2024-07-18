<?php
require_once('includes/connection.php');
?>
<?php
require_once("includes/functions.php");
?>

<?php
if (intval($_GET['subj']) == 0) {
    redirect_to("content.php");
}
$id = $_GET['subj'];
if ($subject = get_subject_by_id($id,$conn)) {


    $query = "DELETE FROM subjects WHERE id = {$id}";
    $result = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) == 1) {
        redirect_to("content.php");
    } else {
        echo "<p> subject deletion failed!</p>";
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