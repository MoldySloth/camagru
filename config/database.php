<?php
    $DB_NAME = "db_camagru";
    $DB_HOST = "127.0.0.1";
    $DB_PORT = "3306";
    $DB_USER = "camagru";
    $DB_PASSWORD = "qQRW1AcHBpMUga5r";
    $DB_DRIVER = "mysql";
    $DB_CHARSET = "utf8";
    $DB_OPT = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $DB_DSN = $DB_DRIVER.":host=".$DB_HOST.":".$DB_PORT.";dbname=".$DB_NAME.";charset=".$DB_CHARSET;
    $DB_DSN_DEFAULT = $DB_DRIVER.":host=".$DB_HOST.":".$DB_PORT.";charset=".$DB_CHARSET;   
