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
}
