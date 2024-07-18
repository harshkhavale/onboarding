<?php
require_once('includes/connection.php');
require_once("includes/functions.php");
include_once("includes/form_functions.php");
confirm_logged_in();

$errors = array();
$message = "";
$username = "";
$password = "";

if (isset($_POST['submit'])) {
    $required_fields = array('username', 'password');
    $errors = array_merge($errors, check_required_fields($required_fields, $_POST));

    $fields_with_lengths = array('username' => 30, 'password' => 30);
    $errors = array_merge($errors, check_max_field_length($fields_with_lengths, $_POST));

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hashed_password = sha1($password);

    if (empty($errors)) {
        $query = "INSERT INTO users (username, hashed_password) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $message = "The user '{$username}' was successfully added.";
                $username = "";
                $password = "";
            } else {
                $message = "Error: Unable to execute query. " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $message = "Error: Unable to prepare query. " . mysqli_error($conn);
        }
    } else {
        $message = (count($errors) == 1) ? "There was 1 error in the form." : "There were " . count($errors) . " errors in the form.";
    }
}

include "includes/header.php";
?>

<table id="structure" class="row" style="height:80vh">
    <tr>
        <td id="navigation" class="col-3 bg-light border b-r" style="height:80vh;">
            <div class="pt-4 p-4" style="height: 100%;">
                <a href="staff.php"><- Menu</a>
            </div>
        </td>
        <td id="page" class="col-9 p-4">
            <h3 class="text-primary">Create New User</h3><br />
            <?php
            if (!empty($message)) {
                echo "<p class=\" text-success\">{$message}</p>";
            }
            ?>
            <form action="new_user.php" method="POST">
                <p>Username: <input type="text" name="username" placeholder="john_doe" id="username" class="form-control p-2" maxlength="30" value="<?php echo htmlentities($username) ?>" /></p>
                <p>Password: <input type="password" maxlength="30" name="password" placeholder="@john2002" id="password" class="form-control p-2" value="<?php echo htmlentities($password) ?>" /></p>
                <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Create">
            </form>
        </td>
    </tr>
</table>

<?php
include "includes/footer.php";
?>