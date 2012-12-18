<?php
class Diary{

    public $db;
    public $uid;
    public $corpId;
    public $workingTime; // 工作时间
    public $dailySend; // 日报默认发送时间
    public $weeklySend;
    public $monthlySend;
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
        $this->uid = 1;
        $this->corpId = 1;
        $this->workingTime = array(1,2,3,4,5);
        $this->dailySend = "18:00";
        $this->dailyRemind = "17:30";
        $this->weeklySend = array(
            'w' => '4', // 周五
            'time' => '11:30'
        );
        $this->weeklyRemind = array(
            'w' => '4', // 周五
            'time' => '12:00'
        );
        $this->monthlySend = array(
            'date' => '20',
            'time' => '18:00'
        );
        $this->monthlyRemind = array(
            'date' => '20',
            'time' => '17:30'
        );
    }
}
