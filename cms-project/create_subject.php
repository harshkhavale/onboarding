<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php
$errors = array();
$required_fields = array('menu_name', 'position', 'visible');
foreach ($required_fields as $fieldname) {
    if (!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
        $errors[] = $fieldname;
    }
}
$fields_with_length = array('menu_name' => 30);
foreach ($fields_with_length as $fieldname => $maxlength) {
    if (strlen(trim($_POST[$fieldname])) > $maxlength) {
        $errors[] = $fieldname;
    }
}
if (!empty($errors)) {
    redirect_to("new_subject.php");
}

$menu_name = $_POST['menu_name'];
$position = $_POST['position'];
$visible = $_POST['visible'];

?>
<?php
$query = "INSERT INTO subjects(menu_name,position,visible) VALUES('{$menu_name}','{$position}','{$visible}')";
if (mysqli_query($conn, $query)) {
    header("Location: content.php");
    exit();
} else {
    echo "<p class=\"text-danger\">subject creation failed</P>";
}
echo "<p>" . mysqli_error($conn) . "</p>";
?>
<?php mysqli_close($conn); ?>