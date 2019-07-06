<?php 
    include('../snake.php');

    $requestData = json_decode(file_get_contents('php://input'), true);
    
    $mySnake = new Snake($requestData["you"]);
    
    header('Content-Type: application/json');
    echo json_encode($mySnake->characterize());
?>