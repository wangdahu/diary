<?php
class Method {
    /**
     * 发送汇报
     */
    public static function sendReport($diary, $params) {
        // 参数
        $args = json_decode(base64_decode($params), true);
        $type = $args['diaryType'];
        $uid = $args['uid'];
        $corpId = $args['corpId'];
        $loginName = $args['loginName'];
        $host = $args['host'];
        $time = $args['nextTime'];
        $weekarray = array("日","一","二","三","四","五","六");
        if($type == 'daily') {
            $diaryType = 1;
            $content = "日报：".date('Y年m月d日', $time)."（周". $weekarray[date("w", $time)]."）";
            $startTime = $time;
            $endTime = $time + 86400 -1;
        }elseif($type == 'weekly') {
            $diaryType = 2;
            $startTime = $mondayTime = date('w', $time) == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
            $endTime = $mondayTime + 7*86400 -1;
            $content = "周报：".date('Y年m月d日', $mondayTime)."--".date('Y年m月d日', $endTime);
        }elseif($type == 'monthly') {
            $diaryType = 3;
            $content = "月报：".date('Y年m月', $time);
            $startTime = strtotime(date('Y-m', $time));
            $endTime = mktime(0, 0, 0, date('m', $time)+1, 1, date('Y', $time)) - 1;
        }
        $configHost = $args['configHost'];
        $url = $configHost."/diary/index.php/my/".$type;
        $title = "写工作日志提醒";

        $user_ids = self::getAllObject($diary, $uid, $corpId, $type);
        $users = self::users($host, $keyCode, $corpId, $user_ids, 0);
        // 发送提醒
        self::send($host, $keyCode, $loginName, array($loginName), $title, $content, $url);
        // 插入下次提醒
        self::insertPolling($diary, $args);

    }

    public static function existsContent($diary, $uid, $startTime, $endTime, $type) {
        $sql = "select * from `diary_info` where `uid` = $uid and `type` = $type and `show_time` between ($startTime and $endTime)";
        if($result = $diary->db->query($sql)){
            if($result->num_rows()) {
                return true;
            }
        }
        return false;
    }

    /**
     * 发送提醒
     */
    public static function sendRemind($diary, $params) {
        // 参数
        $args = json_decode(base64_decode($params), true);
        $type = $args['diaryType'];
        $uid = $args['uid'];
        $corpId = $args['corpId'];
        $loginName = $args['loginName'];
        $host = $args['host'];
        $time = $args['nextTime'];
        $weekarray = array("日","一","二","三","四","五","六");
        if($type == 'daily') {
            $content = "日报：".date('Y年m月d日', $time)."（周". $weekarray[date("w", $time)]."）";
        }elseif($type == 'weekly') {
            $mondayTime = date('w', $time) == 1 ? strtotime("this Monday") : strtotime("-1 Monday");
            $content = "周报：".date('Y年m月d日', $mondayTime)."--".date('Y年m月d日', $mondayTime + 7*86400 -1);
        }elseif($type == 'monthly') {
            $content = "月报：".date('Y年m月', $time);
        }
        $configHost = $args['configHost'];
        $url = $configHost."/diary/index.php/my/".$type;
        $title = "提交工作日志";
        // 发送提醒
        self::send($host, $keyCode, $loginName, array($loginName), $title, $content, $url);
        // 插入下次提醒
        self::insertPolling($diary, $args);

    }

    public static function insertPolling($diary, $args) {
        $uid = $args['uid'];
        $loginName = $args['loginName'];
        $corpId = $args['corpId'];
        $soapUrl = $args['soapUrl'];
        $host = $args['host'];
        $awoke = $args['awoke'];
        $funName = $args['function'];
        $type = $awoke == 1 ? 'report' : 'remind';
        $configHost = $configHost;
        $keyCode = $args['keyCode'];

        $nextInfo = self::nextTime($diary, $uid, $corpId, $type);
        $nextTime = $nextInfo['nextTime'];
        $diaryType = $nextInfo['diaryType'];
        $args = compact('uid', 'corpId', 'diaryType', 'nextTime', 'awoke', 'soapUrl', 'funName', 'host', 'loginName', 'configHost');

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
            'keycode' => $keyCode,  // 验证码
        );
        try {
            $_arr = json_encode($_arr);
            $result = $soap->doAct('polling', $_arr);
            $_msg = json_decode($result, true);
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            var_dump($e);exit();
        }
    }

    /**
     * 获取下次汇报/提醒时间
     */
    public static function nextTime($diary, $uid, $corpId, $type) {
        $now = time();
        $func = $type.'Time';
        $type = ucwords($type);
        if($type == 'remind') {
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
    public static function getAllObject($diary, $uid, $corpId, $type) {
        $reportObject = self::reportObject($diary, $uid); // 我汇报的对象
        $subscribeMy = self::subscribeMy($diary, $uid, $type);
        $objectType = $type == 1 ? 'daily_object' : ($type == 2 ? 'weekly_object' : 'monthly_object');
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
    public static function subscribeMy($diary, $uid, $corpId, $type) {
        $from_uid = $uid;
        $from_dept = $deptId;
        $sql = "select `uid` from `diary_subscribe_object` where `type` = $type and (`from_uid` = $from_uid or `from_dept` = $from_dept)";
        $uids = array();
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $uids[] = (int)$row['uid'];
        }
        return $uids;
    }


    public static function send($host, $keyCode, $loginName, $userLoginNames, $title, $content, $url) {
        $host = $host;
        $soap = new soapClient($host);

        $msg = array(
            'sendname' => $login_name, // 发送人登陆名
            'receive' => $receive, // 接受者id
            'title' => $title, // 标题
            'content' => $content, // 内容
            'url' => $url, // 地址
            'keycode' => $keyCode,  // 验证码
            'style' => 1,  // 验证码
        );
        try {
            $_arr = json_encode($msg);
            $result = $soap->doAct('sendmsgs', $_arr);
            $_msg = json_decode($result, true);
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            var_dump($e);exit();
        }
    }

    public static function users($host, $keyCode, $corpId, $ids, $type=0){
        $soap = new soapClient($host);
        $_arr = array(
            'AccountID' => $corpId, // 企业id
            'id' => $ids,     // 人员id
            'type' => $type,     // 0为人员，1为获取部门人员
            'keycode' => $keyCode,  // 验证码
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

}
