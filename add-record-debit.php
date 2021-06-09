<?php

require_once 'Database.php';

$app_name = 'recordsApp';
$actual_link = "http://$_SERVER[HTTP_HOST]/$app_name";
// add new individual record here
$add_record_btn = isset($_POST['add']);
if ($add_record_btn){
    $id = $_POST['id'];
    if(!empty($id)){
        try{
            $db = new MyDb('records.sqlite', SQLITE3_OPEN_READONLY);
            $db->enableExceptions(true);
            $sql = "SELECT name FROM person where id = $id";
            $ret = $db->querySingle($sql, true);
            $name = $ret['name'];
        }catch (Exception $e){
            echo $e->getMessage() . '<br>';
        }
    }else{
        die('id field is empty');
    }

}else{
    die('no btn is pressed!');
}
?>
<html lang="en">
<head>
    <title>Records | add-record</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="bg-dark">
            <div class="row">
                <div class="col"></div>
                <div class="col"><h1 class="text-center h3 text-light">Add Record</h1></div>
                <div class="col d-flex align-items-center justify-content-end mr-2">
                    <a href="<?php echo "http://$_SERVER[HTTP_HOST]/recordsApp"; ?>" class="btn btn-outline-warning m-1">Home</a>
                </div>
            </div>
        </div>
        <div class="ml-auto mr-auto text-center">
            <p class="h5 text-warning"><?php echo $name; ?></p>
        </div>
        <div class="bg-secondary p-1 text-center">
            <form class="d-flex justify-content-center flex-column align-items-center" action="process/function.php" method="POST">
                <label for="amount">Amount</label>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input class="" type="text" id="amount" name="amount">
                <button class="btn btn-warning" type="submit" name="add_record">ADD</button>
            </form>
        </div>
    </div>
</body>
</html>
