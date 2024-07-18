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
    <td id="navigation" class=" col-3 bg-light border b-r" style="height:80vh;">
            <div class=" pt-4 " style="height: 100%;">
                <?php echo public_navigation($conn, $sel_subj, $sel_page) ?>
                <br />

            </div>

        </td>
        <td id="page" class=" col-9 p-4">
            <h3 class=" text-primary">
                <?php echo $sel_subject_data['menu_name']; ?> </h3>



            <div class="container border bg-light p-4">
                <?php
                $pages = get_pages_for_subject($conn, $sel_subj);

                echo "<ul>";
                while ($row = mysqli_fetch_assoc($pages)) {
                    echo "<li><a class=\"text-dark text-decoration-none\" href='page.php?page={$row['id']}'>{$row['menu_name']}</a></li><br>";
                }
                echo "</ul>";
                ?>
            </div>
            <br />



        </td>
    </tr>



</table>

<?php
include "includes/footer.php";
?>