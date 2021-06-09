<?php

require_once 'Database.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    if(!empty($id)){
        $i = 0;
        $db = new MyDb('records.sqlite', SQLITE3_OPEN_READONLY);
        $db->enableExceptions(true);
//        select sql for name
        try {
            $sql = "SELECT name FROM person where id = $id";
            $db->exec('BEGIN');
            $ret = $db->querySingle($sql, true);
            $db->exec('END');
            $name = $ret['name'];
        }catch (Exception $e){
            echo $e->getMessage() . '<br>';
        }
//        select sql for detail
        try {
            $sql = "SELECT * FROM debit_record where person_id = $id";
            $db->exec('BEGIN');
            $ret = $db->query($sql);
            $db->exec('END');
        }catch (Exception $e){
            echo $e->getMessage() . '<br>';
        }
    }else{
        echo 'NOT allowed!';
    }
}else{
    echo 'something is not right';
}

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

//try {
//    $db = new MyDb('records.sqlite', SQLITE3_OPEN_READONLY);
//    $db->enableExceptions(true);
//    $sql = "SELECT * FROM debit_record where person_id = $id";
//    $ret = $db->query($sql);
//
//}catch(Exception $e){
//    echo $e->getMessage() . '<br>';
//}
?>
<html lang="en">
<head>
    <title>Records | Detail</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="bg-dark">
            <div class="row">
                <div class="col"></div>
                <div class="col"><h1 class="text-center h1 text-light"><?php echo $name; ?> | Detail</h1></div>
                <div class="col d-flex align-items-center justify-content-end mr-2">
                    <a href="<?php echo "http://$_SERVER[HTTP_HOST]/recordsApp"; ?>" class="btn btn-outline-warning m-1">Home</a>
                </div>
            </div>
        </div>
        <div class="bg-secondary p-2">
            <div class="bg-light w-75 ml-auto mr-auto">
                <table class="table table-striped text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $ret->fetchArray(SQLITE3_ASSOC)){ $i++ ?>
                        <tr>
                            <th scope="row"><?php echo $i; ?></th>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['date']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
