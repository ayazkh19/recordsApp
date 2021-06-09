<?php

$submit_btn = isset($_POST['submit']);
if($submit_btn){
//  sanitize input fields. these are not yet!
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    if(!empty($name) && !empty($amount) && !empty($date)){
//        add the record functionality here
//        first create database if not exists
        require_once './Database.php';
        $db = new MyDb();
//        create table first, just in case its the first time
        $sql = "CREATE TABLE IF NOT EXISTS person(
            id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE )";
        $ret = $db->exec($sql);
//        insert person name into person table
        try {
            $sql = "INSERT INTO person(name) VALUES('$name')";
            $db->enableExceptions(true);
            $db->exec('BEGIN');
            $ret = $db->query($sql);
            $db->exec('END');
            $last_insert_id = $db->lastInsertRowID();

            $sql = " SELECT * FROM person WHERE name = '$name' AND id = '$last_insert_id' ";
            $ret = $db->querySingle($sql, true);

//        create table for the return name
            $sql = "CREATE TABLE IF NOT EXISTS '$name' (
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                person_id INT NOT NULL,
                amount TEXT NOT NULL,
                date TEXT NOT NULL)";
            $ret = $db->exec($sql);
//        now insert the remaining form data into individual person table
            $sql =" INSERT INTO '$name'(person_id, amount, date)
                VALUES('$last_insert_id', '$amount', '$date')";
            $db->exec('BEGIN');
            $db->query($sql);
            $db->exec('END');

            $db->close();
            echo 'person add';

        }catch (Exception $e){
            $db->close();
            if ($e->getCode() === 0){
                echo 'this name already exists please enter another name!';
            }
        }
//        var_dump($ret);
//        select from person table
    }
}

?>

<html>
<head>
    <title>records | Add</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="container">
    <div class="bg-dark">
        <h1 class="text-center h1 text-light">Add records</h1>
    </div>
    <div class="bg-secondary p-1 text-center">
        <form class="d-flex justify-content-center flex-column align-items-center" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
            <label for="amount">Amount</label>
            <input type="text" id="amount" name="amount">
            <label for="date">Date</label>
            <input type="text" id="date" name="date">
            <button type="submit" name="submit">ADD</button>
        </form>
    </div>
</div>
</body>
</html>
