<?php
<<<<<<< HEAD
// TODO 1: PREPARING ENVIRONMENT: 1) session 2) functions
session_start();

// TODO 2: ROUTING

// TODO 3: CODE by REQUEST METHODS (ACTIONS) GET, POST, etc. (handle data from request): 1) validate 2) working with data source 3) transforming data

// TODO 4: RENDER: 1) view (html) 2) data (from php)

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
            Home page
        </div>
        <div class="card-body">

            <!-- TODO: render php data   -->

        </div>
    </div>
</div>

</body>
</html>
=======
    if(isset($_GET["search"])) {
        $search = $_GET["search"];

       // TODO: Вставьте ваш apiKey и cx
        $apiKey = "AIzaSyA3RTNa07TzTvtuuHFWYvcFH_OiUSFCyyI";
        $cx = "028bc865f33c5455c"; 

        $url = "https://www.googleapis.com/customsearch/v1?key={$apiKey}&cx={$cx}&q={$search}";

        // Инициализация сеанса cURL
        $ch = curl_init();
        
        // Устанавливаем URL и другие параметры
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Выполнение запроса, получение ответа
        $response = curl_exec($ch);
        
        // Закрытие сеанса cURL
        curl_close($ch);
        
        // Декодируем ответ в формате JSON в массив
        $items = json_decode($response, true);
        
        // Выводим содержимое массива $items
        // var_dump($items);
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>
    </head>
    <body>
        <h2>My Browser</h2>

        <form method="GET" action="/index.php">
            <label for="search">Search:</label>
            <input type="text" id="search" name="search" value=""><br><br>
            <input type="submit" value="Submit">
        </form>

    <?php
    if(isset($items) && is_array($items) && isset($items['items'])) {
        // Перебираем элементы массива $items['items']
        foreach($items['items'] as $item) {
            // Отображаем результаты
            echo "<h3>{$item['title']}</h3>";
            echo "<p>{$item['snippet']}</p>";
            echo "<a href='{$item['link']}'>{$item['link']}</a><br><br>";
        }
    }
    ?>

</body>
</html> 
>>>>>>> 9c2ac5ac971ac783dd6285960417bce859f5b425
