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
                <?php echo $sel_page_data['menu_name']; ?> </h3>





            <p name="content" id="content" style="font-size: small;"><?php echo $sel_page_data['content'] ?></p><br>

            <br />




        </td>
    </tr>



</table>

<?php
include "includes/footer.php";
?>