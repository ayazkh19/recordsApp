<?php

$app_name = 'recordsApp';
$actual_link = "http://$_SERVER[HTTP_HOST]/$app_name";
//echo $actual_link;
//echo "<br>";
//print_r(explode('/', $actual_link, -1));
//echo $_SERVER['PHP_SELF'];
//echo "<br>";
//echo $_SERVER['SERVER_NAME'];
//echo "<br>";
//echo $_SERVER['HTTP_HOST'];
//echo "<br>";
//echo $_SERVER['HTTP_REFERER'];
//echo "<br>";
//echo $_SERVER['HTTP_USER_AGENT'];
//echo "<br>";
//echo $_SERVER['SCRIPT_NAME'];

$submit_btn = isset($_POST['submit']);
$view_msg = '';

if($submit_btn){
//  sanitize input fields. these are not yet!
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $date = date('d/m/yy');;

    if(!empty($name) && !empty($amount) && !empty($date)){
        require_once 'Database.php';

//        open/create a database
        $db = new MyDb('records.sqlite');
        $is_user_unique = true;
        $last_person_id = null;

        $db->enableExceptions(true);
//        create person table
        try {
            $sql = "CREATE TABLE IF NOT EXISTS person(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE )";
            $ret = $db->exec($sql);
        }catch (Exception $e){
            echo 'ERROR!!! ' . $e->getMessage() . ' on line ' . $e->getLine() . '<br>';
        }
//        select from person table to check if the name is already existing or not
        try {
            $sql = "SELECT * FROM person WHERE name = '$name'";
            $ret = $db->querySingle($sql);
//            var_dump($ret);
//            if $ret is not null it means we have same name in the table and this should not be added twice
//            if($ret){
//                $is_user_unique = false;
//            }else{
//                $is_user_unique = true;
//            }
//            var = condition ? 'foo' : 'bar'
            $is_user_unique = $ret ? false : true;
        }catch (Exception $e){
            echo 'ERROR!!! ' . $e->getMessage() . ' on line ' . $e->getLine() . '<br>';
        }
//        echo "<br>is_user_unique: $is_user_unique <br>";
//        user name should be unique
        if($is_user_unique){
//            insert into person
            try {
                $sql = "INSERT INTO person(name) VALUES('$name')";
                $db->exec('BEGIN');
                $ret = $db->query($sql);
                $db->exec('END');
                $last_person_id = $db->lastInsertRowID();
            }catch(Exception $e){
                echo 'ERROR!!! ' . $e->getMessage() . ' on line ' . $e->getLine() . '<br>';
                echo 'ERROR CODE !!: ' . $e->getCode() . '<br>';
            }
//            create debit_record table
            try {
                $sql = "CREATE TABLE IF NOT EXISTS debit_record(
                id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                person_id INT NOT NULL,
                amount INT NOT NULL,
                date TEXT NOT NULL)";
                $ret = $db->exec($sql);
            }catch(Exception $e){
                echo 'ERROR!!! ' . $e->getMessage() . ' on line ' . $e->getLine() . '<br>';
            }
//            insert into debit_record
            try{
                $sql = "INSERT INTO debit_record(person_id, amount, date)
                VALUES('$last_person_id', '$amount', '$date')";
                $db->exec('BEGIN');
                $ret = $db->query($sql);
                $db->exec('END');
            }catch(Exception $e){
                echo 'ERROR!!! ' . $e->getMessage() . ' on line ' . $e->getLine() . '<br>';
            }
            $view_msg = 'Record added!';
        }else{
            $view_msg = 'Name already exists, please enter a different one';
        }
//        close the database in any case
        $db->close();
    }
}

?>

<html lang="en">
<head>
    <title>records | Add</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="bg-dark">
            <div class="row">
                <div class="col"></div>
                <div class="col"><h1 class="text-center h1 text-light">Add Records</h1></div>
                <div class="col d-flex align-items-center justify-content-end mr-2">
                    <a href="<?php echo $actual_link; ?>" class="btn btn-outline-warning m-1">Home</a>
                </div>
            </div>
        </div>
        <?php if($view_msg){ ?>
            <div class="text-center msg">
                <p><?php echo $view_msg; ?></p>
            </div>
        <?php } ?>
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
