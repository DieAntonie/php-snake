<?php
    include_once("../moveResponse.php");
    include_once("../index.php");

    class Snake 
    {
        var $id = "";
        var $name = "";
        var $health = "";
        var $body = array();
        
        function __construct(array $model = ["id" => "snake-id-string", "name" => "Sneky Snek", "health" => 100, "body" => [["x" => 0,"y" => 0]]])
        {
            $this->id = $model["id"];
            $this->name = $model["name"];
            $this->health = $model["health"];
            $this->body = $model["body"];
        }

        function characterize()
        {
            return [
                "color" => "#ff00ff",
                "headType" => "bendr",
                "tailType" => "pixel"
            ];
        }

        function observer($model)
        {
            //logger('logs.txt', json_encode($model));
            
        }

        function move()
        {
            return new MoveResponse();
        }
    }
?>