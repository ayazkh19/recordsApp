<?php

require_once 'Database.php';

$add_person_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $actual_link;

try {
    $db = new MyDb('records.sqlite', SQLITE3_OPEN_READONLY);
    $db->enableExceptions(true);
    $sql = "SELECT * FROM person";
    $ret = $db->query($sql);

}catch(Exception $e){
    echo $e->getMessage() . '<br>';
}


?>
<!--this page should display all the records in debit-->
<html lang="en">
<head>
    <title>Records | Home</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="bg-dark">
            <div class="row">
                <div class="col"></div>
                <div class="col"><h1 class="text-center h1 text-light">Records</h1></div>
                <div class="col d-flex align-items-center justify-content-end mr-2">
                    <a href="<?php echo $add_person_link; ?>add-person-debit.php" class="btn btn-outline-warning m-1">Add person</a>
                </div>
            </div>
        </div>
        <?php while($row = $ret->fetchArray(SQLITE3_ASSOC)){ ?>
            <div class="bg-secondary p-1 text-center w-50 mr-auto ml-auto mb-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <h2 class="h5"><?php echo $row['name']; ?></h2>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="spaced">
                            <a class="btn btn-warning" href="<?php echo "http://$_SERVER[HTTP_HOST]/recordsApp/detail-debit.php?id={$row['id']}" ?>">Detail</a>
                        </div>
                        <div class="spaced">
                            <form class="m-0" action="add-record-debit.php" method="POST">
                                <input type="hidden" value="<?php echo $row['id']; ?>" name="id">
                                <button class="btn btn-outline-warning" name="add" value="submit">Add Record</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>
