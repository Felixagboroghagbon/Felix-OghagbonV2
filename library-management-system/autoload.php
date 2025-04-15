<?php
// /autoload.php

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/classes/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class file not found: $file");
    }
});