<?php 
    include("../snake.php");
    include("../board.php");
    include_once("../index.php");

    $requestData = json_decode(file_get_contents('php://input'), true);

    $mySnake = new Snake($requestData["you"]);
    $myBoard = new Board($requestData["board"]);

    $mySnake->observer($myBoard);

    header('Content-Type: application/json');
    echo json_encode($mySnake->move());
?>