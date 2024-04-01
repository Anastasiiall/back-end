<?php
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