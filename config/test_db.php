<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
    $db['dsn'] = 'mysql:host=127.0.0.1;dbname=todolistapp_test;charset=utf8mb4';
    $db['username'] = 'todo_user';
    $db['password'] = 'DBadmin';

return $db;
