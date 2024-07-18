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
        <td id="navigation" class=" col-3 bg-light border b-r" style="height:80vh;">
            <div class=" pt-4 " style="height: 100%;">
                <?php echo public_navigation($conn, $sel_subj, $sel_page) ?>
                <br />
                <a href="login.php" class=" btn btn-sm btn-primary ms-4">login</a>

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
                    echo " <h3>Welcome to Widget corp!</h3>";
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