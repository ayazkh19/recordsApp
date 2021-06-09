<?php
$db = new SQLite3('test.sqlite');

$table = 'car';

$sql = "CREATE TABLE '$table'(id INTEGER PRIMARY KEY, name TEXT, price INT)";
$db->exec($sql);
$db->exec("INSERT INTO '$table'(name, price) VALUES('Audi', 52642)");
$db->query("INSERT INTO '$table'(name, price) VALUES('Mercedes', 57127)");
$db->exec("INSERT INTO car(name, price) VALUES('Skoda', 9000)");
