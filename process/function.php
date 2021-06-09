<?php
$btn = isset($_POST['add_record']);

if ($btn && isset($_POST['id'])){

    require_once './../Database.php';
    $amount = $_POST['amount'];
    $id = $_POST['id'];
    $date = date('d/m/yy');

    if(!empty($amount) && !empty($id)){

        try {
            $db = new MyDb('./../records.sqlite');
            $db->enableExceptions(true);
            $sql = "INSERT INTO debit_record(person_id, amount, date)
                VALUES ('$id', '$amount', '$date')";
            $db->exec('BEGIN');
            $ret = $db->query($sql);
            $db->exec('END');

        }catch(Exception $e){
            echo $e->getMessage() . ' on line: '. $e->getLine() . '<br>';
        }
        $db->close();
//        file_get_content can also be use for pass variables in urls
//        sessions are another way to access the variable through out the session.
        header("Location: http://localhost/recordsApp/detail-debit.php?id=$id");
        die();

    }else{
        die('amount should not be empty!');
    }

}else{
    die('no btn is pressed !');
}