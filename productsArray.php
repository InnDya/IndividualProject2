<?php

$products_json = file_get_contents("./data/products.json");
$productsList = json_decode($products_json, true)['Products'];

$products = array();
$categories = array();
foreach ($productsList as $p) {
    $product = array(
        "id" => $p['Id'],
        "title" => $p['Title'],
        "description" => $p['Description'],
        "price" => $p['Price'],
        "image" => $p['ImageUrl'],
        "category" => $p['Category']
    );
    array_push($products, $product);
    array_push($categories, $p['Category']);
}

$categories = array_unique($categories);
