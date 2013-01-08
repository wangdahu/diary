<?php
class Diary{

    public $db;
    public $uid;
    public $corpId;
    public $workingTime; // 工作时间
    public $dailyReport; // 日报默认汇报时间
    public $weeklyReport;
    public $monthlyReport;
    public $dailyRemind; // 日报默认提醒时间
    public $weeklyRemind;
    public $monthlyRemind;

    public function __construct(){
        $mysqli = new mysqli('localhost', 'root', '.', 'diary');
        if($mysqli->connect_error){
            die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }
        $mysqli->query("SET NAMES 'utf8'");
        $this->db = $mysqli;
        $this->uid = 3;
        $this->corpId = 1;
        $this->deptId = 1;
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
}
