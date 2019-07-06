<?php
    include_once("../index.php");

    class Board 
    {
        var $height = "";
        var $width = "";
        var $food = array();
        var $snakes = array();
        
        function __construct(array $model = ["height" => 15, "width" => 15,"food" => [["x" => 1,"y" => 3]],"snakes" => [["id" => "snake-id-string", "name" => "Sneky Snek", "health" => 90,"body" => [[ "x" => 1,"y" => 3]]]]])
        {
            $this->height = $model["height"];
            $this->width = $model["width"];
            $this->food = $model["food"];
            $this->snakes = $model["snakes"];
        }
    }
?>