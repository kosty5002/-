<?php
session_start();
$products = $_SESSION['products'] ?? [];
$productId = $_POST['id'] ?? null;

if ($productId) {
    $products = array_filter($products, fn($product) => $product['id'] != $productId);
    $_SESSION['products'] = array_values($products);
}

header('Location: add-product.php');
exit;
?>
