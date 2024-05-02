<?php
session_start();

require_once 'config.php';

if (!empty($_SESSION['auth'])) {
    header('Location: /admin.php');
    die;
}

$infoMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $infoMessage = "This user already exists! Go to the login page. <a href='/login.php'>Страница входа</a>";
        } else {
            $stmt = $conn->prepare("INSERT INTO user (email, password, date) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $email, $password);
            if ($stmt->execute()) {
                header('Location: ./guestbook.php');
                die;
            } else {
                $infoMessage = 'Error during user registration';
            }
        }
    } else {
        $infoMessage = 'Fill out the registration form!';
    }
}

$sqlSelect = "SELECT email, password, date FROM user";
$resultSelect = mysqli_query($conn, $sqlSelect);

if ($resultSelect) {
    $users = mysqli_fetch_all($resultSelect, MYSQLI_ASSOC);
} else {
    $infoMessage = 'Error during query execution';
}
?>

<!DOCTYPE html>
<html>
<?php require_once 'sectionHead.php' ?>
<body>
<div class="container">
    <?php require_once 'sectionNavbar.php' ?>
    <br>
    <div class="card card-primary">
        <div class="card-header bg-success text-light">
        Registration form
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email"/>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password"/>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="formRegister" value="Submit"/>
                </div>
            </form>
            <?php
            if ($infoMessage) {
                echo '<hr/>';
                echo "<span style='color:red'>$infoMessage</span>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
