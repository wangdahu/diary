<?php
$baseDir = dirname(dirname(__FILE__));
include_once $baseDir."/DB/shared/ez_sql_core.php";

// Include ezSQL database specific component
include_once $baseDir."/DB/mysql/ez_sql_mysql.php";
// Initialise database object and establish a connection
// at the same time - db_user / db_password / db_name / db_host
class Diary{

    public $db;
    public $uid;
    public $corpId;

    public function __construct(){
        $this->db = new ezSQL_mysql('root','.','diary','localhost');
        $this->uid = 1;
        $this->corpId = 1;
    }
}
