<?php
class DiaryUser{

    public static function getInfo($uid){
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            $host = "http://113.106.88.164:14132/Interface/www/op/stdserver.php?wsdl";

            $soap = new soapClient($host);

            return array(
                'photo' => '../../source/images/img_01.png',
                'username' => $uid,
                'dept_name' => '技术部',
                'corp_id' => 131785,
            );
            $_arr = array(
                'AccountID' => self::corpId, // 企业id
                'userid' => array($uid),     // 人员id
                'keycode' => self::keyCode,  // 验证码
            );

            try {
                $_arr = json_encode($_arr);
                $result = $soap->doAct('getuserinfo',$_arr);
                $_msg = json_decode($result,true);
                $_msg_arr = json_decode($_msg['msg'],true);
                var_dump($_msg_arr);exit();
            } catch(Exception $e) {
                var_dump($e);exit();
            }
            echo "<pre>"; var_dump($_msg_arr);exit;
        }else {
            return array(
                'photo' => '../../source/images/img_01.png',
                'username' => $uid,
                'dept_name' => '技术部',
                'corp_id' => 1,
            );
        }
    }
}
