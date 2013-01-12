<?php
class DiaryUser{

    public static function base($ids){
        $config = Diary::getConfig(); // 网站的基本配置
        $host = "http://".$config['host'].":".$config['port']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();
        $_arr = array(
            'AccountID' => $_session_arr['entInfo']['AccountID'], // 企业id
            'userid' => $ids,     // 人员id
            'keycode' => $config['keyCode'],  // 验证码
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

    public static function getInfo($id){
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            $result = self::base(array($id));
            return array(
                'photo' => '../../source/images/img_01.png',
                'UserName' => $result[0]['UserName'],
                'dept_name' => DiaryDept::getName($result[0]['DepartmentID']),
                'corp_id' => $result[0]['AccountID'],
            );
        }else {
            return array(
                'photo' => '../../source/images/img_01.png',
                'UserName' => $id,
                'dept_name' => '技术部',
                'corp_id' => 1,
            );
        }
    }

    public static function getUsers($user_ids){
        // $users = self::base($user_ids);
        $result = array();
        foreach($user_ids as $id){
            $result[$id] = self::getInfo($id);
        }
        return $result;
    }
}
