<?php
require_once('includes/connection.php');
require_once('includes/functions.php');
find_selected_page($conn);
include 'includes/header.php';

if (isset($_GET['subj'])) {
    $subject_id = $_GET['subj'];
}
?>

<table id="structure" class="row">
    <tr>
        <td id="navigation" class="col-3 bg-light">
        <div class="pt-4 p-4" style="height: 100%;">
                <a href="staff.php"><- Back</a>
            </div>
            <?php echo navigation($conn, $sel_subj, $sel_page); ?>
            <br />
                <a href="new_subject.php" class="  text-decoration-none text-light p-1 rounded-full font-bold btn btn-primary btn-sm">+ add a new subject</a>

        </td>
        <td id="page" class="col-9 p-4">
            <h3 class="text-primary">Add Page</h3>
            <form action="create_page.php?subj=<?php echo $subject_id; ?>" method="post" class="container bg-light border p-4">
                <p>Page name:<input type="text" placeholder="Enter Page name" name="menu_name" value="" id="menu_name" /></p>

                <p>Position:<select name="position">
                        <?php
                        $page_set = get_pages_for_subject($conn, $subject_id);
                        $page_counts = mysqli_num_rows($page_set);
                        for ($count = 1; $count <= $page_counts + 1; $count++) {
                            echo "<option value=\"{$count}\">{$count}</option>";
                        }
                        ?>
                    </select></p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1" /> Yes
                </p>
                <p>Content:
                    <textarea name="content" rows="6" cols="40" id="content"></textarea>
                </p>
                <input type="submit" class="btn btn-primary p-1 font-bold" value="Add Page">
                <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>" />
            </form>
            <br />
            <a href="content.php">&larr; Cancel</a>
        </td>
    </tr>
</table>

<?php
include 'includes/footer.php';
?>