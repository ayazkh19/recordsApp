<?php
class MyDb extends SQLite3
{

    public function __construct($_database_name, $flags = null)
    {
        if ($flags){
            $this->open($_database_name, $flags);
//            echo 'with flags';
        }else{
            $this->open($_database_name);
//            echo 'without flags';
        }
    }

}