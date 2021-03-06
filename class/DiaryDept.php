<?php
class DiaryDept{

    public static function base($ids) {
        $config = Diary::getConfig(); // 网站的基本配置
        $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();

        $_arr = array(
            'AccountID' => $_session_arr['entInfo']['AccountID'], // 企业id
            'depid' => $ids,     // 部门id
            'keycode' => $config['keyCode'],  // 验证码
        );

        try {
            $_arr = json_encode($_arr);
            $result = $soap->doAct('getdepinfo', $_arr);
            $_msg = json_decode($result, true);
            $_msg_arr = json_decode($_msg['msg'], true);
        } catch(Exception $e) {
            var_dump($e);
            exit();
        }
        return $_msg_arr;
    }

    public static function getInfo($dept_id) {
        return array(
            'Name' => $dept_id,
        );
    }

    public static function getName($dept_id) {
        $deptInfo = self::base(array($dept_id));
        return $deptInfo[0]['Name'];
    }

    public static function getDepts($dept_ids) {
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            $depts = self::base($dept_ids);
        }else {
            foreach($dept_ids as $dept_id){
                $depts[] = self::getInfo($dept_id);
            }
        }
        return array_combine($dept_ids, $depts);
    }
}
