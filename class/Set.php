<?php
/**
 * 查询设置的不同类别的设置
 *
 */
class Set{
    /**
     * 查询工作时间
     */
    public static function workingTime($diary, $uid){
        $sql = "select `working_time` from `diary_set` where `uid` = $uid";
        if($result = $diary->db->query($sql)){
            $row = $result->fetch_row();
            if($row){
                return json_decode($row[0], true);
            }else{
                return $diary->workingTime;
            }
        }
    }

    /**
     * 设置工作时间
     */
    public static function saveWorkingTime($diary, $value){
        $uid = $diary->uid;
        $workingTime = json_encode($value);
        $sql = "replace into `diary_set` (`uid`, `working_time`) values ($uid, '".$workingTime."')";
        return $diary->db->query($sql);
    }

    /**
     * 查询工作时间
     */
    public static function reportTime($diary){
        $uid = $diary->uid;
        $sql = "select * from `diary_report_set` where `uid` = $uid";
        $reportSet = array();
        if($result = $diary->db->query($sql)){
            $row = $result->fetch_object();
            if($row){
                $reportSet['dailyReport'] = json_decode($row->daily, true);
                $reportSet['weeklyReport'] = json_decode($row->weekly, true);
                $reportSet['monthlyReport'] = json_decode($row->monthly, true);
            }else{
                $reportSet['dailyReport'] = $diary->dailyReport;
                $reportSet['weeklyReport'] = $diary->weeklyReport;
                $reportSet['monthlyReport'] = $diary->monthlyReport;
            }
        }
        return $reportSet;
    }

    /**
     * 保存时间和提醒方式
     */
    public static function saveReportTime($diary, $daily, $weekly, $monthly){
        $uid = $diary->uid;
        $sql = "replace into `diary_report_set` (`uid`, `daily`, `weekly`, `monthly`) values ($uid, '".json_encode($daily)."', '".json_encode($weekly)."', '".json_encode($monthly)."')";
        return $diary->db->query($sql);
    }
}
