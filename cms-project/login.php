<?php
require_once('includes/session.php');
require_once('includes/connection.php');
require_once("includes/functions.php");

if (logged_in()) {
    redirect_to("staff.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id,username FROM users WHERE username = ? AND hashed_password = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        $hashed_password = sha1($password);
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $_SESSION['id'] = $username;
            $_SESSION['username'] = $username;

            redirect_to("staff.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }

        mysqli_stmt_close($stmt);
    } else {
        $error_message = "Login failed. Please try again later.";
    }
}else{
    if(isset($_GET['logout']) && $_GET['logout'] == 1){
        $message = "You have been logged out!";
    }
}

include "includes/header.php";
?>

<table id="structure" class="row" style="height:80vh">
    <tr>
        <td id="navigation" class="col-3 bg-light border b-r" style="height:80vh;">
            <div class="pt-4 p-4" style="height: 100%;">
                <a href="index.php"><- Back</a>
            </div>
        </td>
        <td id="page" class="col-9 p-4">
            <h3 class="text-primary">Staff Login</h3><br />
            <?php
            if (isset($error_message)) {
                echo "<p class='text-danger'>{$error_message}</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <p>Username: <input type="text" name="username" placeholder="john_doe" id="username" class="form-control p-2" required /></p>
                <p>Password: <input type="password" name="password" placeholder="********" id="password" class="form-control p-2" required /></p>
                <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm" value="Login">
            </form>
        </td>
    </tr>
</table>

<?php
include "includes/footer.php";
?>