<?php
session_start();
include('../connect.php');

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $price = floatval($_POST['price']);
    
    addToCart($id, $name, $price);
}

function addToCart($id, $name, $price) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => 1
        ];
    }
}

echo json_encode(["status" => "success"]);
?>

<?php
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<div class="cart">
    <i class="bi bi-cart"></i> <span><?php echo $cartCount; ?></span>
</div>