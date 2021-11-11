<?php

// Connect to database
function db_connect() {
    $dbURI = 'mysql:host=localhost;port=8889;dbname=wildlife-watcher';
    return new PDO($dbURI, 'user1', 'user1');
}