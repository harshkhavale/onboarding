<?php
require_once('includes/connection.php');
?>
<?php
require_once("includes/functions.php");
?>

<?php
find_selected_page($conn);
?>
<?php
include "includes/header.php";
?>

<table id="structure" class="row">
    <tr>
        <td id="navigation" class=" col-3 bg-light ">
            <?php echo navigation($conn, $sel_subj, $sel_page) ?>
            <br />
                <a href="new_subject.php" class="  text-decoration-none text-light p-1 rounded-full font-bold btn btn-primary btn-sm">+ add a new subject</a>


        </td>
        <td id="page" class=" col-9 p-4">
            <h3 class=" text-primary">
                Add Subject </h3>
            <form action="create_subject.php" method="post" class=" container bg-light border p-4">
                <p>Subject name:<input type="text" name="menu_name" value="" id="menu_name" /></p>
                <p>Position:<select name="position">
                        <?php
                        $subject_set = get_all_subjects($conn);
                        $subject_counts = mysqli_num_rows($subject_set);
                        for ($count = 1; $count <= $subject_counts + 1; $count++) {
                            echo "<option value=\"{$count}\">{$count}</option>";
                        }
                        ?>
                    </select></p>
                <p>
                    Visible:
                    <input type="radio" name="visible" value="0" /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1"> Yes
                </p>
                <input type="submit" class="btn btn-primary p-1 font-bold" value="Add Subject">

            </form>

            <br />
           

            <a href="content.php">
                <- Cancle </a>



        </td>
    </tr>
</table>
<?php
include "includes/footer.php";
?>