<?php
session_start();

// Подключение к базе данных
require_once 'config.php';

// Функция для проверки наличия данных в суперглобальном массиве $_POST
function validate_post_data($data) {
    return isset($data['email']) && isset($data['name']) && isset($data['text']);
}

// Функция для сохранения комментария в базу данных
function save_comment($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO comments (email, name, text, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $data['email'], $data['name'], $data['text']);
    $stmt->execute();
}

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (validate_post_data($_POST)) {
        // Валидация успешна, сохраняем комментарий в базе данных
        save_comment($conn, $_POST);
        // Перенаправляем пользователя на ту же страницу, чтобы избежать повторной отправки формы
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit();
    } else {
        echo "Ошибка: Не все поля формы заполнены.";
    }
}

?>

<!DOCTYPE html>
<html>

<?php require_once 'sectionHead.php' ?>

<body>

<div class="container">

    <!-- navbar menu -->
    <?php require_once 'sectionNavbar.php' ?>
    <br>

    <!-- guestbook section -->
    <div class="card card-primary">
        <div class="card-header bg-primary text-light">
            GuestBook form
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">

                    <!-- HTML форма для добавления комментариев -->
                    <form method="post">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="text">Comment:</label>
                            <textarea class="form-control" id="text" name="text"></textarea>
                        </div><br>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="card card-primary">
        <div class="card-header bg-body-secondary text-dark">
            Сomments
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">

                    <!-- Отображение комментариев из базы данных -->
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM comments ORDER BY date DESC");
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $email = htmlspecialchars($row['email']);
                            $name = htmlspecialchars($row['name']);
                            $text = htmlspecialchars($row['text']);
                            echo "<p><strong>Email:</strong> {$email}</p>";
                            echo "<p><strong>Name:</strong> {$name}</p>";
                            echo "<p><strong>Comment:</strong> {$text}</p>";
                            echo "<hr>";
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
