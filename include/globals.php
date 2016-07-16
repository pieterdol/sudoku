<?php

if (!defined('IS_LOCAL')) {
    define(
        'IS_LOCAL', 
        (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) ? true : false
    );
}

if (!defined('APP_PATH')) {
    // PHP_SELF = /index.php
    define(
        'APP_PATH', 
        $_SERVER['PHP_SELF'] . "/../"
    );
}