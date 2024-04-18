<?php
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

// Функция для проверки наличия данных в суперглобальном массиве $_POST
function validate_post_data($data) {
    return isset($data['email']) && isset($data['name']) && isset($data['text']);
}

// Функция для сохранения комментария в файл
function save_comment($data) {
    $file = 'comments.csv';
    $row = array($data['email'], $data['name'], $data['text']);
    $fp = fopen($file, 'a');
    fputcsv($fp, $row);
    fclose($fp);
}

// Функция для получения комментариев из файла
function get_comments() {
    $file = 'comments.csv';
    $comments = array();
    if (($handle = fopen($file, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $comments[] = $data;
        }
        fclose($handle);
    }
    return $comments;
}

// TODO 2: ROUTING
if (empty($_SESSION['auth'])) {
    header('Location: /index.php');
    die;
}

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (validate_post_data($_POST)) {
        // Валидация успешна, сохраняем комментарий
        save_comment($_POST);
        // Перенаправляем пользователя на ту же страницу, чтобы избежать повторной отправки формы
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    } else {
        echo "Ошибка: Не все поля формы заполнены.";
    }
}

// TODO 4: RENDER: 1) view (html) 2) data (from php)

$comments = get_comments();

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
        <div class="card-header bg-warning text-light">
            Admin
        </div>
        <div class="card-body">

            <!-- TODO: render php data   -->
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

            <!-- Отображение комментариев -->
            <div class="mt-3">
                <?php if (!empty($comments)) : ?>
                    <h4>Comments:</h4>
                    <ul>
                        <?php foreach ($comments as $comment) : ?>
                            <li>
                                <strong>Email:</strong> <?php echo $comment[0]; ?><br>
                                <strong>Name:</strong> <?php echo $comment[1]; ?><br>
                                <strong>Comment:</strong> <?php echo $comment[2]; ?><hr>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No comments yet.</p>
                <?php endif; ?>
            </div>

            <!-- Отображение сообщения об ошибке валидации -->
            <?php if (isset($error_message)) : ?>
                <div class="alert alert-danger mt-3"><?php echo $error_message; ?></div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>
