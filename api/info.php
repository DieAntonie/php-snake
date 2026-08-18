<?php 
    header('Content-Type: application/json');
    echo json_encode([
        "apiVersion" => "1",
        "author" => "Chris Antonie Pieterse",
        "color" => "#ff00ff",
        "headType" => "bendr",
        "tailType" => "pixel"
    ]);
?>