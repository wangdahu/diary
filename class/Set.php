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
     * 查询汇报设置时间
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
     * 保存汇报的时间和提醒方式
     */
    public static function saveReportTime($diary, $daily, $weekly, $monthly){
        $uid = $diary->uid;
        $sql = "replace into `diary_report_set` (`uid`, `daily`, `weekly`, `monthly`) values ($uid, '".json_encode($daily)."', '".json_encode($weekly)."', '".json_encode($monthly)."')";
        return $diary->db->query($sql);
    }

    /**
     * 查询提醒时间设置
     */
    public static function remindTime($diary){
        $uid = $diary->uid;
        $sql = "select * from `diary_remind_set` where `uid` = $uid";
        $remindSet = array();
        if($result = $diary->db->query($sql)){
            $row = $result->fetch_object();
            if($row){
                $remindSet['dailyRemind'] = json_decode($row->daily, true);
                $remindSet['weeklyRemind'] = json_decode($row->weekly, true);
                $remindSet['monthlyRemind'] = json_decode($row->monthly, true);
            }else{
                $remindSet['dailyRemind'] = $diary->dailyRemind;
                $remindSet['weeklyRemind'] = $diary->weeklyRemind;
                $remindSet['monthlyRemind'] = $diary->monthlyRemind;
            }
        }
        return $remindSet;
    }

    /**
     * 保存提醒的时间和提醒方式
     */
    public static function saveRemindTime($diary, $daily, $weekly, $monthly){
        $uid = $diary->uid;
        $sql = "replace into `diary_remind_set` (`uid`, `daily`, `weekly`, `monthly`) values ($uid, '".json_encode($daily)."', '".json_encode($weekly)."', '".json_encode($monthly)."')";
        return $diary->db->query($sql);
    }

    /**
     * 获取订阅对象
     */
    public static function subscribeObject($diary, $uid = null){
        $uid = $uid ? $uid : $diary->uid;

        $subscribe['daily_object']['user'] = $subscribe['daily_object']['dept'] = $subscribe['weekly_object']['user'] = $subscribe['weekly_object']['dept'] = $subscribe['monthly_object']['user'] = $subscribe['monthly_object']['dept'] = array(12,15,10,7,8,9);
        $sql = "select * from `diary_subscribe_object` where `uid` = $uid";
        $result = $diary->db->query($sql);
        $row = $result->fetch_object();
        if($row){
            $subscribe['daily_object'] = json_decode($row->daily_object, true);
            $subscribe['weekly_object'] = json_decode($row->weekly_object, true);
            $subscribe['monthly_object'] = json_decode($row->monthly_object, true);
        }
        return $subscribe;
    }

    /**
     * 保存订阅对象
     */
    public static function saveSubscribeObject($diary, $daily, $weekly, $monthly){
        $uid = $diary->uid;
        $sql = "replace into `diary_subscribe_object` (`uid`, `daily_object`, `weekly_object`, `monthly_object`) values ($uid, '".json_encode($daily)."', '".json_encode($weekly)."', '".json_encode($monthly)."')";
        return $diary->db->query($sql);
    }

    /**
     * 获取汇报对象
     */
    public static function reportObject($diary){
        $uid = $diary->uid;

        $sql = "select * from `diary_report_object` where `uid` = $uid";
        $result = $diary->db->query($sql);

        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            if($row['type'] == 1){ // 日报汇报对象
                if($row['to_uid']){
                    $report['daily_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $report['daily_object']['dept'][] = $row['to_dept'];
                }
            }elseif($row['type'] == 2){ // 周报汇报对象
                if($row['to_uid']){
                    $report['weekly_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $report['weekly_object']['dept'][] = $row['to_dept'];
                }
            }else{ // 月报汇报对象
                if($row['to_uid']){
                    $report['monthly_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $report['monthly_object']['dept'][] = $row['to_dept'];
                }
            }
        };
        $report['daily_object']['user'] = $report['daily_object']['dept'] = $report['weekly_object']['user'] = $report['weekly_object']['dept'] = $report['monthly_object']['user'] = $report['monthly_object']['dept'] = array(5,8,6,7,1,2,3,4);
        return $report;
    }

    /**
     * 保存汇报对象
     */
    public static function saveReportObject($diary, $daily_users, $daily_depts, $weekly_users, $weekly_depts, $monthly_users, $monthly_depts){
        $uid = $diary->uid;
        $sql = "delete from `diary_report_object` where `uid` = $uid";
        $diary->db->autocommit(false);
        $diary->db->query($sql);

        foreach($daily_users as $to_id){
            $sql = "insert into `diary_report_object` (`uid`, `to_uid`, `type`) values ($uid, $to_id, 1)";
            $diary->db->query($sql);
        }
        foreach($daily_depts as $to_dept){
            $sql = "insert into `diary_report_object` (`uid`, `to_dept`, `type`) values ($uid, $to_dept, 1)";
            $diary->db->query($sql);
        }
        foreach($weekly_users as $to_id){
            $sql = "insert into `diary_report_object` (`uid`, `to_uid`, `type`) values ($uid, $to_id, 2)";
            $diary->db->query($sql);
        }
        foreach($weekly_depts as $to_dept){
            $sql = "insert into `diary_report_object` (`uid`, `to_dept`, `type`) values ($uid, $to_dept, 2)";
            $diary->db->query($sql);
        }
        foreach($monthly_users as $to_id){
            $sql = "insert into `diary_report_object` (`uid`, `to_uid`, `type`) values ($uid, $to_id, 3)";
            $diary->db->query($sql);
        }
        foreach($monthly_depts as $to_dept){
            $sql = "insert into `diary_report_object` (`uid`, `to_dept`, `type`) values ($uid, $to_dept, 3)";
            $diary->db->query($sql);
        }
        $diary->db->commit();
    }

    /**
     * 获取当前用户在团队日志中显示的人员对象
     * @param $type 1:日报， 2:周报， 3:月报
     */
    public static function teamShowObject($diary, $type = 1){
        $uid = $diary->uid;
        $deptId = $diary->deptId;
        // 订阅对象
        $subSql = "select * from `diary_subscribe_object` where uid = $uid";
        $subResult = $diary->db->query($subSql);

        $subscribeObject['daily_object']['user'] = $subscribeObject['daily_object']['dept'] = $subscribeObject['weekly_object']['user'] = $subscribeObject['weekly_object']['dept'] = $subscribeObject['monthly_object']['user'] = $subscribeObject['monthly_object']['dept'] = array();

        if($row = $subResult->fetch_object()){
            $subscribeObject['daily_object'] = json_decode($row->daily_object, true);
            $subscribeObject['weekly_object'] = json_decode($row->weekly_object, true);
            $subscribeObject['monthly_object'] = json_decode($row->monthly_object, true);
        }

        // 汇报对象
        $reportSql = "select * from `diary_report_object` where to_uid = $uid or to_dept = $deptId";
        $reportResult = $diary->db->query($reportSql);

        $reportObject['daily_object']['user'] = $reportObject['daily_object']['dept'] = $reportObject['weekly_object']['user'] = $reportObject['weekly_object']['dept'] = $reportObject['monthly_object']['user'] = $reportObject['monthly_object']['dept'] = array();
        while($row = $reportResult->fetch_array(MYSQLI_ASSOC)){
            if($row['type'] == 1){ // 日报汇报对象
                if($row['to_uid']){
                    $reportObject['daily_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $reportObject['daily_object']['dept'][] = $row['to_dept'];
                }
            }elseif($row['type'] == 2){ // 周报汇报对象
                if($row['to_uid']){
                    $reportObject['weekly_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $reportObject['weekly_object']['dept'][] = $row['to_dept'];
                }
            }else{ // 月报汇报对象
                if($row['to_uid']){
                    $reportObject['monthly_object']['user'][] = $row['to_uid'];
                }elseif($row['to_dept']){
                    $reportObject['monthly_object']['dept'][] = $row['to_dept'];
                }
            }
        }
        $merge_data = array_merge_recursive($reportObject, $subscribeObject);
        if($type == 1){
            $teamShowUser = array_unique($merge_data['daily_object']['user']);
            $teamShowDept = array_unique($merge_data['daily_object']['dept']);
        }elseif($type == 2){
            $teamShowUser = array_unique($merge_data['weekly_object']['user']);
            $teamShowDept = array_unique($merge_data['weekly_object']['dept']);
        }else{
            $teamShowUser = array_unique($merge_data['monthly_object']['user']);
            $teamShowDept = array_unique($merge_data['monthly_object']['dept']);
        }
        foreach($teamShowDept as $dept){
            // 获取部门下的所有人
        }
        return $teamShowUser;
    }
}
