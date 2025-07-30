<?php
header('Content-Type: application/json');
include('../connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = " SELECT food.*, provider.name_provider, provider.email_provider, provider.number_provider, provider.address_provider
                        FROM food
                        JOIN provider ON food.id_provider = provider.id_provider
                        WHERE food.id_food = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "No food found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid ID"]);
}

$conn->close();
?>
