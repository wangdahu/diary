<?php
class Method {

    /*
    *返回值函数
    */
    public static function output($_flag,$_msg,$_stats_tag,$_is_json = false) {
        $_arr = array(
            'flag' => $_flag,
            'msg' => $_msg,
            'info' => $_stats_tag
        );
        if ($_is_json) {
            return JSON_ENCODE($_arr);
        }else {
            return $_arr;
        }
    }

    /**
     * 发送汇报
     */
    public static function sendReport($params) {
        try {
            $diary = classdb::getdb();
            // 参数
            $args = json_decode(base64_decode($params), true);

            $type = $args['diaryType'];
            $uid = $args['uid'];
            $corpId = $args['corpId'];
            $deptId = $args['deptId'];
            $loginName = $args['loginName'];
            $host = $args['host'];
            $time = $args['nextTime'];
            $keycode = $args['keycode'];
            $weekarray = array("日","一","二","三","四","五","六");
            if($type == 'daily') {
                $currentDate = date('Y-m-d');
                $diaryType = 1;
                $content = "日报：".date('Y年m月d日', $time)."（周". $weekarray[date("w", $time)]."）";
                $startTime = strtotime($currentDate);
                $endTime = $startTime + 86400 -1;
            }elseif($type == 'weekly') {
                $currentDate = date('Y-W');
                $diaryType = 2;
                $startTime = $mondayTime = date('w', $time) == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
                $endTime = $startTime + 7*86400 -1;
                $content = "周报：".date('Y年m月d日', $mondayTime)."--".date('Y年m月d日', $endTime);
            }elseif($type == 'monthly') {
                $currentDate = date('Y-m');
                $diaryType = 3;
                $content = "月报：".date('Y年m月', $time);
                $startTime = strtotime(date('Y-m', $time));
                $endTime = mktime(0, 0, 0, date('m', $time)+1, 1, date('Y', $time)) - 1;
            }
            $configHost = $args['configHost'];
            $url = $configHost."/diary/index.php/team/".$type."?uid=".$uid."&startTime=".$startTime;
            $title = "汇报提醒";
            $existsContents = self::existsContent($diary, $uid, $startTime, $endTime, $diaryType);
            $isReported = self::checkReport($diary, $type, $currentDate, $uid);
            if($existsContents && !$isReported) { // 有内容切位汇报才发送汇报
                $user_ids = self::getAllObject($diary, $uid, $deptId, $type);
                if($user_ids) {
                    $reportTime = time();
                    $users = self::users($host, $keycode, $corpId, $user_ids, 0);
                    // 判断是否需要汇报（未写日志，或者已汇报都不提醒）
                    $loginNameArr = array();
                    if($users) {
                        foreach($users as $user) {
                            $loginNameArr[] = $user['LoginName'];
                            $object = $user['PID'];
                            $selectSql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$type' and `object` = $object and `date` = '$currentDate'";
                            $result = $diary->db->query($selectSql);
                            if(!$result->fetch_assoc()) {
                                $sql = "insert into `diary_report_record` (`uid`, `type`, `object`, `date`, `report_time`, `repay`) values ($uid, '$type', $object, '$currentDate', $reportTime, 0)";
                                $diary->db->query($sql);
                            }
                        }
                        // 发送提醒
                        $_send_status = self::send($host, $keycode, $loginName, $loginNameArr, $title, $content, $url, 'views');
                        if(!$_send_status) {
                            return method::output(1, "消息推送失败！", '501', true);
                        }
                    }

                }
            }


            // 插入下次提醒
            $_insert_status = self::insertPolling($diary, $args);
            if($_insert_status) {
                return method::output(1, "操作成功！", '500', true);
            }
            return method::output(0, "操作失败！", '502', true);

        }catch(Exception $e) {
            return method::output(0, $e->getMessage(), '501', true);
        }

    }

    public static function existsContent($diary, $uid, $startTime, $endTime, $type) {
        $sql = "select * from `diary_info` where `uid` = $uid and `type` = $type and `show_time` between $startTime and $endTime limit 1";
        if($result = $diary->db->query($sql)){
            if($result->fetch_array(MYSQLI_ASSOC)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 发送提醒
     */
    public static function sendRemind($params) {
        try {
            $diary = classdb::getdb();

            // 参数
            $args = json_decode(base64_decode($params), true);

            $type = $args['diaryType'];
            $uid = $args['uid'];
            $corpId = $args['corpId'];
            $deptId = $args['deptId'];
            $loginName = $args['loginName'];

            $host = $args['host'];
            $time = $args['nextTime'];
            $weekarray = array("日","一","二","三","四","五","六");

            if($type == 'daily') {
                $type = 'index';
                $currentDate = date('Y-m-d');
                $startTime = strtotime($currentDate);
                $content = "日报：".date('Y年m月d日', $time)."（周". $weekarray[date("w", $time)]."）";
            }elseif($type == 'weekly') {
                $startTime = $mondayTime = date('w', $time) == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
                $content = "周报：".date('Y年m月d日', $mondayTime)."--".date('Y年m月d日', $mondayTime + 7*86400 -1);
            }elseif($type == 'monthly') {
                $startTime = strtotime(date('Y-m', $time));
                $content = "月报：".date('Y年m月', $time);
            }
            $configHost = $args['configHost'];
            $url = $configHost."/diary/index.php/my/".$type."?startTime=".$startTime;
            $title = "写工作日志提醒";

            // 发送提醒
            $keycode = $args['keycode'];

            $msgArr = array('daily'=>'day', 'weekly'=>'week', 'monthly'=>'month');
            $opttype = $msgArr[$type];
            $_send_status = self::send($host, $keycode, $loginName, array($loginName), $title, $content, $url, $opttype);

            // by HJ
            if(!$_send_status) {
                return method::output(0, "消息推送失败！", '501', true);
            }

            // 插入下次提醒
            $_insert_status = self::insertPolling($diary, $args);

            if($_insert_status) {
                return method::output(1, "操作成功！", '500', true);
            }

            return method::output(0, "操作失败！", '502', true);
        }catch(Exception $e) {
            return method::output(0, $e->getMessage(), '503', true);
        }

    }

    public static function insertPolling($diary, $args) {
        try{
            $uid = $args['uid'];
            $loginName = $args['loginName'];
            $corpId = $args['corpId'];
            $deptId = $args['deptId'];
            $soapUrl = $args['soapUrl'];
            $host = $args['host'];
            $awoke = $args['awoke'];
            $funName = $args['funName'];

            $type = $awoke == 1 ? 'report' : 'remind';
            $configHost = $args['configHost'];

            $keycode = $args['keycode'];

            $nextInfo = self::nextTime($diary, $uid, $corpId, $type);

            $nextTime = $nextInfo['nextTime'];
            $diaryType = $nextInfo['diaryType'];
            $args = compact('uid', 'corpId', 'deptId', 'diaryType', 'nextTime', 'awoke', 'soapUrl', 'funName', 'host', 'loginName', 'configHost', 'keycode');

            $soap = new soapClient($host);
            $argsStr = base64_encode(json_encode($args));
            $_arr = array(
                'AccountID' => $corpId, // 企业id
                'userid' => $uid,     // 人员id
                'func' => $funName, // sendRepot,sendRemind
                'args' => $argsStr,
                'type' => 0,     // 0为日志
                'awoke' => $awoke, // 提醒类型（0=>'remind', 1=>'report'）
                'soapurl' => $soapUrl,
                'times' => date('Y-m-d H:i:s', $nextTime),     // 0为人员，1为获取部门人员
                'keycode' => $keycode,  // 验证码
            );

            $_arr = json_encode($_arr);
            $result = $soap->doAct('polling', $_arr);
            $_msg = json_decode($result, true);

            if($_msg['flag']) {
                return true;
            }else {
                return false;
            }
        }catch(Exception $e) {
            return false;
        }
    }

    /**
     * 获取下次汇报/提醒时间
     */
    public static function nextTime($diary, $uid, $corpId, $type) {
        $now = time();
        $func = $type.'Time';
        $type = ucwords($type);
        if($type == 'Remind') {
            $time = self::remindTime($diary, $uid, $corpId);
        }else {
            $time = self::reportTime($diary, $uid, $corpId);
        }

        // 日报下次提交时间
        $dailyTime = strtotime(date('Y-m-d ').$time['daily'.$type]['hour'].":".$time['daily'.$type]['minute']);
        $dailyTime = $dailyTime < $now ? $dailyTime+86400 : $dailyTime;

        // 周报下次提交时间
        $w = date('w') ? date('w') : 7; // 周日转换成7
        $weeklyHourTime = $time['weekly'.$type]['hour'].":".$time['weekly'.$type]['minute'];
        if($w == $time['weekly'.$type]['w']) {
            $weeklyTime = strtotime(date('Y-m-d ').$weeklyHourTime);
        }elseif ($w < $time['weekly'.$type]['w']){
            $weeklyTime = strtotime(date('Y-m-d ').$weeklyHourTime) + ($time['weekly'.$type]['w']-$w)*86400;
        }else{
            $weeklyTime = strtotime(date('Y-m-d ').$weeklyHourTime) - ($w-$time['weekly'.$type]['w'])*86400;
        }

        $weeklyTime = $weeklyTime < $now ? $weeklyTime+7*86400 : $weeklyTime;
        // 月报下次提交时间
        $monthlyTime = strtotime(date('Y-m-').$time['monthly'.$type]['date']." ".$time['monthly'.$type]['hour'].":".$time['monthly'.$type]['minute']);
        $nextMonthTime = mktime(date('H', $monthlyTime), date('i', $monthlyTime), 0, date('m', $monthlyTime)+1, date('d', $monthlyTime), date('Y', $monthlyTime));
        $monthlyTime = $monthlyTime < $now ? $nextMonthTime : $monthlyTime;

        $nextTime = min($dailyTime, $weeklyTime, $monthlyTime);
        $diaryType = 'daily';
        if($nextTime == $weeklyTime) {
            $diaryType = 'weekly';
        }elseif($nextTime == $monthlyTime) {
            $diaryType = 'monthly';
        }
        return compact('diaryType', 'nextTime');
    }


    /**
     * 查询汇报设置时间
     */
    public static function reportTime($diary, $uid){
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
     * 查询提醒时间设置
     */
    public static function remindTime($diary, $uid, $corpId){
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
     * 获取要汇报和已订阅我的所有对象
     */
    public static function getAllObject($diary, $uid, $deptId, $type) {

        $reportObject = self::reportObject($diary, $uid); // 我汇报的对象

        if ( ! $reportObject)
        {
            return false;
        }

        $subscribeMy = self::subscribeMy($diary, $uid, $deptId, $type);
        $objectType = $type.'_object';
        $allUsers = array_unique(array_merge($reportObject[$objectType]['user'], $subscribeMy));
        $allDepts = array_unique($reportObject[$objectType]['dept']);
        return $allUsers;
    }

    /**
     * 获取汇报对象
     */
    public static function reportObject($diary, $uid){
        $sql = "select * from `diary_report_object` where `uid` = $uid";
        $result = $diary->db->query($sql);

        if(!$result) {
            return false;
        }

        $report['daily_object']['user'] = $report['daily_object']['dept'] = $report['weekly_object']['user'] = $report['weekly_object']['dept'] = $report['monthly_object']['user'] = $report['monthly_object']['dept'] = array();

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
        return $report;
    }

    /**
     * 获取订阅我的日志的所有对象
     */
    public static function subscribeMy($diary, $uid, $deptId, $type) {
        $from_uid = $uid;
        $from_dept = $deptId;
        $diaryType = 1;
        if($type == 'weekly') {
            $diaryType = 2;
        }else if($type == 'monthly') {
            $diaryType = 3;
        }
        $sql = "select `uid` from `diary_subscribe_object` where `type` = $diaryType and (`from_uid` = $from_uid or `from_dept` = $from_dept)";
        $uids = array();
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $uids[] = (int)$row['uid'];
        }
        return $uids;
    }


    public static function send($host, $keycode, $loginName, $userLoginNames, $title, $content, $url, $opttype) {
        try{
            $soap = new soapClient($host);
            $msg = array(
                'sendname' => $loginName, // 发送人登陆名
                'receive' => $userLoginNames, // 接受者账户(数组)
                'title' => $title, // 标题
                'content' => $content, // 内容
                'url' => $url, // 地址
                'keycode' => $keycode,  // 验证码
                'style' => 1,  // 验证码
                'showtype' => 'weblog',
                'opttype' => $opttype,
            );

            $_arr = json_encode($msg);
            $result = $soap->doAct('sendmsgs', $_arr);
            $_msg = json_decode($result, true);
            if($_msg['flag']) {
                return true;
            }else{
                return false;
            }
        }catch(Exception $e) {
            return false;
        }
    }

    public static function users($host, $keycode, $corpId, $ids, $type=0){
        $soap = new soapClient($host);
        $_arr = array(
            'AccountID' => $corpId, // 企业id
            'id' => $ids,     // 人员id
            'type' => $type,     // 0为人员，1为获取部门人员
            'keycode' => $keycode,  // 验证码
        );

        try {
            $_arr = json_encode($_arr);
            $result = $soap->doAct('getuserinfo', $_arr);
            $_msg = json_decode($result, true);
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            var_dump($e);
            exit();
        }
        return $_msg_arr;
    }


    /**
     * 是否汇报
     */
    public static function checkReport($diary, $type, $currentDate, $uid){
        $sql = "select * from `diary_report_record` where `uid` = $uid and `type` = '$type' and `date` = '$currentDate' limit 1";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_assoc()){
            return true;
        }
        return false;
    }
}
