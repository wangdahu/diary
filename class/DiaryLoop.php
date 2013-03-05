<?php
class DiaryLoop{

    public static function insertPolling($awoke, $funName) {
        $config = Diary::getConfig(); // 网站的基本配置
        $keycode = $config['keyCode'];
        $configHost = "http://".$config['host'];
        $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();
        $uid = $_session_arr['userInfo']['PID'];
        $loginName = $_session_arr['userInfo']['LoginName'];
        $corpId = $_session_arr['entInfo']['AccountID'];
        $deptId = $_session_arr['entInfo']['DeptID'];
        $soapUrl = "http://".$config['host']."/diary/soap/stdserver.php?wsdl";
        $type = $awoke == 1 ? 'report' : 'remind';
        $nextInfo = DiarySet::nextTime($type);
        $nextTime = $nextInfo['nextTime'];
        $diaryType = $nextInfo['diaryType'];

        $args = compact('uid', 'corpId', 'deptId', 'diaryType', 'nextTime', 'awoke', 'soapUrl', 'funName', 'host', 'loginName', 'configHost', 'keycode');
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
     * 删除循环
     */
    public static function delPolling($awoke) {
        $config = Diary::getConfig(); // 网站的基本配置
        $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();
        $uid = $_session_arr['userInfo']['PID'];
        $corpId = $_session_arr['entInfo']['AccountID'];

        $_arr = array(
            'AccountID' => $corpId, // 企业id
            'userid' => $uid,     // 人员id
            'type' => 0,     // 0为日志
            'awork' => $awoke, // 提醒类型（'report', 'remind'）
            'keycode' => $config['keyCode'],  // 验证码
        );
        try {
            $_arr = json_encode($_arr);
            $result = $soap->doAct('delpolling', $_arr);
            $_msg = json_decode($result, true);
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            var_dump($e);exit();
        }
    }

    public static function testMy($diary, $testType) {
        echo "test".$testType;
        $sql = "select * from `t_polling_plugin` where `pid` = 609";
        $result = $diary->db->query($sql);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            $args = json_decode(base64_decode($row['args']), true);
        }
        if($testType == 'testReport') {
            $sendFunction = 'sendReport';
            $sql = "select * from `t_polling_plugin` where `pid` = 609 and `awoke` = 1 ";
        }else {
            $sendFunction = 'sendRemind';
            $sql = "select * from `t_polling_plugin` where `pid` = 609 and `awoke` = 0 ";
        }
        $result = $diary->db->query($sql);
        $config = Diary::getConfig(); // 网站的基本配置
        $keyCode = $config['keyCode'];
        $configHost = "http://".$config['host'];
        $host = "http://".$config['host']."/diary/soap/stdserver.php?wsdl";
        $soap = new soapClient($host);
        while($row = $result->fetch_array(MYSQLI_ASSOC)){
            try {
                $msg = $soap->doAct($sendFunction, $row['args']);
                echo "<pre>"; var_dump(date('Y-m-d H:i:s'));
                echo "<pre>"; var_dump($msg);
                $_msg = json_decode($msg, true);
                echo "<pre>"; var_dump($_msg);exit;
                $_msg_arr = json_decode($_msg['msg'], true);
            } catch(Exception $e) {
                echo "<pre>"; var_dump(2222);
                var_dump($e);exit();
            }
        }
    }

    public static function testCreateDaily($diary) {
        $content = 'test create daily';
        $uid = $diary->uid;
        $AccountID = $diary->corpId;
        $args = compact('content', 'uid', 'AccountID');
        $args = base64_encode(json_encode($args));
        $config = Diary::getConfig(); // 网站的基本配置
        $keyCode = $config['keyCode'];
        $host = "http://".$config['host']."/diary/soap/stdserver.php?wsdl";;
        $soap = new soapClient($host);
        try {
            $msg = $soap->doAct('writeDaily', $args);
            echo "<pre>"; var_dump($msg);
            $_msg = json_decode($msg, true);
            echo "<pre>"; var_dump($_msg);exit;
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            echo "<pre>"; var_dump(2222);
            var_dump($e);exit();
        }
    }
}
