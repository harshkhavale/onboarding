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

<table id="structure" class="row" style="height:80vh">
    <tr>
        <td id="navigation" class=" col-3 bg-light " style="height:80vh;">
            <div class=" d-flex flex-column justify-item-start">
            <div class="pt-4 p-4" style="height: 100%;">
                <a href="index.php"><- Back</a>
            </div>    
            <?php echo navigation($conn, $sel_subj, $sel_page) ?>
                <br />
                <a href="new_subject.php" class="  text-decoration-none text-light p-1 rounded-full font-bold btn btn-primary btn-sm">+ add a new subject</a>

            </div>

        </td>
        <td id="page" class=" col-9 p-4">
            <h3 class=" text-primary">
                <?php
                if (!is_null($sel_subject_data)) {
                    echo  $sel_subject_data['menu_name'];
                } elseif (!is_null($sel_page_data)) {
                    echo $sel_page_data['menu_name'];
                } else {
                    echo "Please select a subject or page to edit.";
                }

                ?><br />

            </h3>
            <div>
                <?php

                ?>
            </div>


        </td>
    </tr>
</table>
<?php
include "includes/footer.php";
?>