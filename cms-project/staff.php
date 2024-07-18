<?php
require_once('includes/session.php');

require_once('includes/functions.php');

?>
<?php
confirm_logged_in();
?>
<?php include "includes/header.php"; ?>

<table id="structure" class="row" style="height:80vh">
    <tr>
        <td id="navigation" class="col-3 bg-light border b-r" style="height:80vh;">
            <div class="pt-4 p-4" style="height: 100%;">
                <a href="index.php"><- Home</a>
            </div>
        </td>
        <td id="page" class=" col-9 p-4">
            <h3 class=" text-primary">
                Staff Menu



            </h3>
            <div class="container p-4 border">
                <p>Welcome to the staff area, <?php echo "<strong class=\"text-primary underline text-xl\">" . $_SESSION['username'] . "</strong>" ?></p>
                <ul>
                    <li><a href="content.php" class=" text-dark">Manage Website Content</a></li>
                    <li><a href="new_user.php" class=" text-dark">Add Staff User</a></li>
                    <li><a href="logout.php" class=" text-dark">Logout</a></li>


                </ul>
                <?php

                ?>
            </div>



        </td>
    </tr>
</table>
<?php
include "includes/footer.php";
?>