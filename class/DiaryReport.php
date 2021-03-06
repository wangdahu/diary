<?php
/**
 * 汇报日志的记录
 */
class DiaryReport {

    /**
     * 是否汇报
     */
    public static function checkReport($diary, $type, $date, $uid = null){
        if(!$uid){
            $uid = $diary->uid;
        }
        $sql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$type' and `date` = '$date' limit 1";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_assoc()){
            return true;
        }
        return false;
    }

    /**
     * 汇报列表
     */
    public static function getReportList($diary, $type, $date, $uid = null){
        if(!$uid){
            $uid = $diary->uid;
        }
        $list = array();
        $sql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$type' and `date` = '$date'";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $list[] = $row;
        }
        return $list;
    }

    /**
     * 汇报总人数
     */
    public static function getReportCount($diary, $type, $date, $uid = null){
        if(!$uid){
            $uid = $diary->uid;
        }
        $sql = "select count(*) from `diary_report_record` where `uid` = $uid and `type` = '$type' and `date` = '$date'";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_row()){
            return $row[0];
        }
        return 0;
    }

    /**
     * 获取当前时间汇报的人员列表
     */
    public static function getDateReports($diary, $type, $date) {
        $sql = "SELECT `uid` FROM `diary_report_record` where `type` = '".$type."' and `date` = '".$date."' group by `uid`";
        $result = $diary->db->query($sql);
        $users = array();
        while($row = $result->fetch_assoc()){
            $users[] = $row['uid'];
        }
        return $users;
    }
}
