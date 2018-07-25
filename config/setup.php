<?php
    include 'database.php';

    // create db
    try {
        $pdo = new PDO($DB_DSN_DEFAULT, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO
        $createsql = "CREATE DATABASE IF NOT EXISTS `".$DB_NAME."`"; // Create statement
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Database created (".$DB_NAME.").\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create database ".$DB_NAME." with error: \n".$e->getMessage();
        exit(-1);
    }

    // create users table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` varchar (20) NOT NULL default '',
            `password` varchar (64) NOT NULL default '',
            `salt` varchar (32) NOT NULL default '',
            `name` varchar (50) NOT NULL default '',
            `email` varchar (150) NOT NULL default '',
            `notify_me` binary (1) NOT NULL default '1',
            `profile_img` varchar (150) NOT NULL default '',
            `active` binary (1) NOT NULL default '0',
            `joined` DATETIME,
            `group` int
        );"; 
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Users Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create Users Table with error: \n".$e->getMessage();
    }

    // create groups table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `groups` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` varchar (20),
            `permissions` text
        );"; 
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Group Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create Group Table with error: \n".$e->getMessage();
    }

    // create confirmation table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `confirm_account` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` int,
                `key_hash` varchar (150) NOT NULL default '',
                `email` varchar (150) NOT NULL default ''
            );";
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: User Confirmation Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create User Confirmation Table with error: \n".$e->getMessage();
    }

    // create users_session table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `users_session` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` int,
            `hash` varchar (150) NOT NULL default ''
        );"; 
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: User Session Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create User Session Table with error: \n".$e->getMessage();
    }

    // create gallery table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `gallery` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT,
                `image_url` varchar (150) NOT NULL default '',
                `image_overlay` varchar (255) NOT NULL default '',
                `tags` varchar (150) NOT NULL default '',
                `likes` INT NOT NULL default 0,
                `created` DATETIME
            );";
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Gallery Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create Gallery Table with error: \n".$e->getMessage();
    }

    // create image_likes table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `image_likes` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` int,
                `image_id` int,
                `created` DATETIME
            );";
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Image_likes Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create Image_likes Table with error: \n".$e->getMessage();
    }

    // create image_comments table
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "CREATE TABLE ".$DB_NAME." . `image_comments` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` int,
                `image_id` int,
                `comment` varchar (150) NOT NULL default '',
                `created` DATETIME
            );";
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: Image_comments Table created.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not create Image_comments Table with error: \n".$e->getMessage();
    }

    // prime permissions user
    try {
        $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPT); // Create PDO based off of created DB
        $createsql = "INSERT INTO ".$DB_NAME." . `groups` (`id`, `name`, `permissions`) 
            VALUES (NULL, 'Standard user', ''), (NULL, 'Administrator', '{\"admin\": 1, \"moderator\": 1}');";
        $pdo->exec($createsql); // EXEC create
        echo "SUCCESS: User Table Primed.\n";
    } catch (PDOException $e) {
        echo "FAILURE: Could not prime User Table with error: \n".$e->getMessage();
    }

    require_once 'user-dummy-data.php';
	header('Location: ../login/index.php');

