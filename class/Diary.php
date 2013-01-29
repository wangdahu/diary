<?php
class Diary{

    public $db;
    public $uid;
    public $corpId;
    public $userInfo;
    public $workingTime; // 工作时间
    public $dailyReport; // 日报默认汇报时间
    public $weeklyReport;
    public $monthlyReport;
    public $dailyRemind; // 日报默认提醒时间
    public $weeklyRemind;
    public $monthlyRemind;
    public $isSetReport;

    public function __construct(){
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            $db_config = Kohana::config('database.wisetong');
            $contection = $db_config['connection'];
            $db_host = $contection['host'];
            $db_port = $contection['port'];
            $db_user = $contection['user'];
            $db_pass = $contection['pass'];
            $db_database = $contection['database'];
            $character_set = $db_config['character_set'];
            $mysqli = new mysqli($db_host.':'.$db_port, $db_user, $db_pass, $db_database);
            if($mysqli->connect_error){
                die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
            $_session_arr = Session::instance()->get();
            $this->userInfo = $_session_arr['userInfo'];
            $this->LoginName = $_session_arr['userInfo']['LoginName'];
            $this->entInfo = $_session_arr['entInfo'];
            $this->uid = $this->userInfo['PID'];
            $this->corpId = $this->entInfo['AccountID'];
            $this->deptId = $this->entInfo['DeptID'];
        }else{
            $character_set = 'utf8';
            $mysqli = new mysqli('localhost', 'root', '.', 'diary');
            if($mysqli->connect_error){
                die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
            $this->uid =  1;
            $this->LoginName = 'admin';
            $this->corpId = 131785;
            $this->deptId = 1;
        }

        $mysqli->query("SET NAMES '".$character_set."'");
        $this->db = $mysqli;
        $this->workingTime = array(1,2,3,4,5);
        $this->dailyReport = array('hour' => '18', 'minute' => '0', 'way' => array('remind'));
        $this->dailyRemind = array('hour' => '17', 'minute' => '30', 'way' => array('remind'));
        $this->weeklyReport = array(
            'w' => '5', // 周五
            'hour' => '12',
            'minute' => '0',
            'way' => array('remind'),
        );
        $this->weeklyRemind = array(
            'w' => '5', // 周五
            'hour' => '11',
            'minute' => '30',
            'way' => array('remind'),
        );
        $this->monthlyReport = array(
            'date' => '20',
            'hour' => '18',
            'minute' => '0',
            'way' => array('remind'),
        );
        $this->monthlyRemind = array(
            'date' => '20',
            'hour' => '17',
            'minute' => '30',
            'way' => array('remind'),
        );
    }

    public static function getConfig() {
        return array(
            'host' => Kohana::config('config.domain'),
            'port' => '14132',
            'keyCode' => 'gzRN53VWRF9BYUXo',
        );
    }

}
