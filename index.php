<?php 
include('productsArray.php'); 

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Referrer-Policy: no-referrer");

$errors = array();

// Проверяю условия и если не выполняются то записываю сообщения с ошибками в массив $errors
$show = isset($_GET['show']) ? htmlspecialchars($_GET['show']) : 20;
if ($show < 1 || $show > 20) {
    $message = array('Show' => 'Show must be between 1 and 20');
    array_push($errors, $message);
}

$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : null;
if ($category != null && !in_array($category, $categories)) {
    $message = array('Category' => 'Category not found');
    array_push($errors, $message);
} 

// Если массив с ошибками не пустой, то возвращаем сообшения с ошибками 
if (!empty($errors)) {
    echo json_encode($errors);
}
// иначе возвращаем данные
else {
    if ($category != null) {
        $products = array_filter($products, function($p) use ($category) {
            return $p['category'] == $category;
        });
    }
    $result_show = array();
    // array_rand - метод, перемешивающий индексы случайным образом. Здесь указываем с каким массивом 
    // работаем, и сколько возвращать случайных индексов.
    // Мы берём меньшее из $show (то что задал пользователь) и count($products) (количество элементов
    // в отфильтрованном массиве продуктов) чтобы метод срабатывал без ошибок когда 
    // $show больше чем длина массива $products
    $rand_keys = array_rand($products, min($show, count($products)));
    // если только 1 продукт, то вернёт просто число, а не массив(что соответственно будет ошибкой)
    // и поэтому делается проверка: массив? всё хорошо, нет - заворачивает в массив. 
    $rand_keys = is_array($rand_keys) ? $rand_keys : array($rand_keys);
    
    // форич работает только с массивами, поэтому провека выше необходима
    foreach($rand_keys as $key) {
        array_push($result_show, $products[$key]);
    }
    
    // закодировать в json
    echo json_encode($result_show);
}

?>