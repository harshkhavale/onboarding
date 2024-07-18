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
if (isset($_POST['submit'])) {


    $errors = array();
    $required_fields = array('menu_name', 'position', 'visible');
    foreach ($required_fields as $fieldname) {
        if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && $_POST[$fieldname] != 0)) {
            $errors[] = $fieldname;
        }
    }
    $fields_with_length = array('menu_name' => 30);
    foreach ($fields_with_length as $fieldname => $maxlength) {
        if (strlen(trim($_POST[$fieldname])) > $maxlength) {
            $errors[] = $fieldname;
        }
    }
    if (empty($errors)) {
        $id = $_GET['subj'];
        $menu_name = $_POST['menu_name'];
        $position = $_POST['position'];
        $visible = $_POST['visible'];
        $query = "UPDATE subjects SET menu_name = '{$menu_name}',position = '{$position}',visible = '{$visible}' WHERE id = {$id}";
        $result = mysqli_query($conn, $query);
        if (mysqli_affected_rows($conn) == 1) {
            $message = "The subject has been updated.";
        } else {
            $message = "The subject update failed.";
            $message .= "<br/>" . mysqli_error($conn);
        }
    } else {
        $message = "There were " . count($errors) . "errors in the form.";
    }
}

?>

<?php
find_selected_page($conn);
?>
<?php
include "includes/header.php";
?>

<table id="structure" class="row">
    <tr>
        <td id="navigation" class=" col-3 bg-light p-2 border b-r ">
            <div class=" absolute top-0">
            <div class="pt-4 p-4" style="height: 100%;">
                <a href="staff.php"><- Back</a>
            </div>
                <?php echo navigation($conn, $sel_subj, $sel_page) ?>
                <br />
                <a href="new_subject.php" class="  text-decoration-none text-light p-1 rounded-full font-bold btn btn-primary btn-sm">+ add a new subject</a>

            </div>


        </td>
        <td id="page" class=" col-9 p-4">
            <h3 class=" text-primary">
                Edit Subject:<?php echo $sel_subject_data['menu_name']; ?> </h3>
            <?php if (!empty($message)) {
                echo "<p class=\" text-secondary\">" . $message . "</p>";
            } ?>
            <?php
            if (!empty($errors)) {
                echo "<p class = \"text-danger\">";
                echo "please review the following fields<br/>";
                foreach ($errors as $error) {
                    echo " - " . $error . "<br/>";
                }
                echo "</p>";
            }
            ?>
            <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject_data['id']) ?>" method="post" class=" container bg-light border p-4">
                <p>Subject name:<input type="text" name="menu_name" value="<?php echo htmlspecialchars($sel_subject_data['menu_name']); ?>" id="menu_name" /></p>
                <p>Position:<select name="position">
                        <?php
                        $subject_set = get_all_subjects($conn);
                        $subject_counts = mysqli_num_rows($subject_set);
                        for ($count = 1; $count <= $subject_counts + 1; $count++) {
                            echo "<option value=\"{$count}\"";
                            if (
                                $count == $sel_subject_data['position']

                            ) {
                                echo " selected";
                            }
                            echo ">  {$count}</option>";
                        }
                        ?>
                    </select></p>
                <p>
                    Visible:
                    <input type="radio" name="visible" value="1" <?php if ($sel_subject_data['visible'] == 1) {
                                                                        echo "checked";
                                                                    }
                                                                    ?>>Yes
                    <input type="radio" name="visible" value="0" <?php if ($sel_subject_data['visible'] == 0) {
                                                                        echo "checked";
                                                                    } ?>>No

                </p>
                <input type="submit" name="submit" class="btn btn-primary btn-sm" value="Edit Subject">
                <a href="delete_subject.php?subj=<?php echo urlencode($sel_subject_data['id']); ?>" onclick="return confirm('Are you sure?')" class=" p-1 btn btn-danger btn-sm">delete</a>
            </form>
            <p class=" text-primary">Pages in this subject</p>
            <div class="container border bg-light p-4">
                <?php
                $pages = get_pages_for_subject($conn, $sel_subj);

                echo "<ul>";
                while ($row = mysqli_fetch_assoc($pages)) {
                    echo "<li><a href='edit_page.php?page={$row['id']}'>{$row['menu_name']}</a></li><br>";
                }
                echo "<li><a href='new_page.php?subj={$sel_subj}'>create new page + </a></li>";
                echo "</ul>";
                ?>
            </div>
            <br />
            <a href="content.php">
                <- Cancle </a>



        </td>
    </tr>



</table>

<?php
include "includes/footer.php";
?>