<?php
    function logger($file = 'logs.txt', $data)
    {
        file_put_contents($file, $data.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
?>