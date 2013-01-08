<?php
/**
 * 查询设置的不同类别的设置
 *
 */
class DiarySet{
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
    public static function reportTime($diary, $uid=null){
        $uid = $uid ? $uid : $diary->uid;
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
    public static function subscribeObject($diary){
        $uid = $diary->uid;

        $sql = "select * from `diary_subscribe_object` where `uid` = $uid";
        $result = $diary->db->query($sql);

        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            if($row['type'] == 1){ // 日报汇报对象
                if($row['from_uid']){
                    $subscribe['daily_object']['user'][] = $row['from_uid'];
                }elseif($row['from_dept']){
                    $subscribe['daily_object']['dept'][] = $row['from_dept'];
                }
            }elseif($row['type'] == 2){ // 周报汇报对象
                if($row['from_uid']){
                    $subscribe['weekly_object']['user'][] = $row['from_uid'];
                }elseif($row['from_dept']){
                    $subscribe['weekly_object']['dept'][] = $row['from_dept'];
                }
            }else{ // 月报汇报对象
                if($row['from_uid']){
                    $subscribe['monthly_object']['user'][] = $row['from_uid'];
                }elseif($row['from_dept']){
                    $subscribe['monthly_object']['dept'][] = $row['from_dept'];
                }
            }
        };
        // $subscribe['daily_object']['user'] = $subscribe['daily_object']['dept'] = $subscribe['weekly_object']['user'] = $subscribe['weekly_object']['dept'] = $subscribe['monthly_object']['user'] = $subscribe['monthly_object']['dept'] = array(5,8,1,2,3,4);
        return $subscribe;
    }

    /**
     * 保存订阅对象
     */
    public static function saveSubscribeObject($diary, $daily_users, $daily_depts, $weekly_users, $weekly_depts, $monthly_users, $monthly_depts){
        $uid = $diary->uid;
        $sql = "delete from `diary_subscribe_object` where `uid` = $uid";
        $diary->db->autocommit(false);
        $diary->db->query($sql);

        foreach($daily_users as $from_id){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_uid`, `type`) values ($uid, $from_id, 1)";
            $diary->db->query($sql);
        }
        foreach($daily_depts as $from_dept){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_dept`, `type`) values ($uid, $from_dept, 1)";
            $diary->db->query($sql);
        }
        foreach($weekly_users as $from_id){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_uid`, `type`) values ($uid, $from_id, 2)";
            $diary->db->query($sql);
        }
        foreach($weekly_depts as $from_dept){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_dept`, `type`) values ($uid, $from_dept, 2)";
            $diary->db->query($sql);
        }
        foreach($monthly_users as $from_id){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_uid`, `type`) values ($uid, $from_id, 3)";
            $diary->db->query($sql);
        }
        foreach($monthly_depts as $from_dept){
            $sql = "insert into `diary_subscribe_object` (`uid`, `from_dept`, `type`) values ($uid, $from_dept, 3)";
            $diary->db->query($sql);
        }
        $diary->db->commit();
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
        $subscribeObject = self::subscribeObject($diary);
        // $reportObject = self::reportObject($diary);
        $reportObject = array();
        $merge_data = array_merge_recursive($reportObject, $subscribeObject);
        $objectType = $type == 1 ? 'daily_object' : ($type == 2 ? 'weekly_object' : 'monthly_object');
        $teamShowUser = array_unique($merge_data[$objectType]['user']);
        $teamShowDept = array_unique($merge_data[$objectType]['dept']);
        foreach($teamShowDept as $dept){
            // 获取部门下的所有人
        }
        return $teamShowUser;
    }

    /**
     * 获取订阅我的日志的所有对象
     */
    public static function subscribeMy($diary, $type) {
        $from_uid = $diary->uid;
        $from_dept = $diary->deptId;
        $sql = "select `uid` from `diary_subscribe_object` where `type` = $type and (`from_uid` = $from_uid or `from_dept` = $from_dept)";
        $uids = array();
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $uids[] = (int)$row['uid'];
        }
        return $uids;
    }

    /**
     * 获取要汇报和已订阅我的所有对象
     */
    public static function getAllObject($diary, $type) {
        $reportObject = self::reportObject($diary); // 我汇报的对象
        $subscribeMy = self::subscribeMy($diary, $type);
        $objectType = $type == 1 ? 'daily_object' : ($type == 2 ? 'weekly_object' : 'monthly_object');
        $allUsers = array_unique(array_merge($reportObject[$objectType]['user'], $subscribeMy));
        $allDepts = array_unique(array_merge($reportObject[$objectType]['dept'], $subscribeMy));

        return $allUsers;
    }
}
