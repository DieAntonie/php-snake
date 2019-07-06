<?php
    class MoveResponse
    {
         var $move;
         function __construct($direction = 'right')
         {
              $this->move = $direction;
         }
    }
?>