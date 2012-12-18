<?php
class Diary{

    public $db;
    public $uid;
    public $corpId;
    public $workingTime; // 工作时间
    public $dailyReport; // 日报默认汇报时间
    public $dailyReportRemindWay; // 日报汇报的默认提醒方式
    public $dailyRemindRemindWay; // 日报提醒的默认提醒方式
    public $weeklyReport;
    public $weeklyReportRemindWay;
    public $weeklyRemindRemindWay;
    public $monthlyReport;
    public $monthlyReportRemindWay;
    public $monthlyRemindRemindWay;
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
        $this->dailyReport = array('hour' => '18', 'minute' => '0');
        $this->dailyRemind = array('hour' => '17', 'minute' => '30');
        $this->weeklyReport = array(
            'w' => '4', // 周五
            'hour' => '12',
            'minute' => '0'
        );
        $this->weeklyRemind = array(
            'w' => '4', // 周五
            'hour' => '11',
            'minute' => '30'
        );
        $this->monthlyReport = array(
            'date' => '20',
            'hour' => '18',
            'minute' => '0'
        );
        $this->monthlyRemind = array(
            'date' => '20',
            'hour' => '17',
            'minute' => '30'
        );
        $this->dailyReportRemindWay = $this->weeklyReportRemindWay = $this->monthlyReportRemindWay = array('remind');
        $this->dailyRemindRemindWay = $this->weeklyRemindRemindWay = $this->monthlyRemindRemindWay = array('remind');
    }
}
