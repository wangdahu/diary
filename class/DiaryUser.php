<?php
class DiaryUser{

    public static function base($ids, $type=0){
        if($ids) {
            $config = Diary::getConfig(); // 网站的基本配置
            $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
            $soap = new soapClient($host);
            $_session_arr = Session::instance()->get();
            $_arr = array(
                'AccountID' => $_session_arr['entInfo']['AccountID'], // 企业id
                'id' => $ids,     // 人员id
                'type' => $type,     // 0为人员，1为获取部门人员
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
    }

    public static function getInfo($id){
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            $result = self::base(array($id));
            if($result) {
                return array(
                    'photo' => '../../source/images/img_01.png',
                    'UserName' => $result[0]['UserName'],
                    'dept_name' => $result[0]['depname'],
                    'corp_id' => $result[0]['AccountID'],
                    'LoginName' => $result[0]['LoginName'],
                    'Title' => $result[0]['Title'],
                );
            }else {
                return self::delUserInfo($id);
            }
        }else {
            return array(
                'photo' => '../../source/images/img_01.png',
                'UserName' => $id,
                'dept_name' => '技术部',
                'corp_id' => 131785,
                'LoginName' => 'admin',
                'Title' => '经理',
            );
        }
    }

    public static function getUsers($user_ids){
        $user_ids = array_unique($user_ids);
        $result = array();
        if(file_exists(dirname(dirname(dirname(__FILE__)))."/vars.php")) {
            if($user_ids){
                $users = self::base($user_ids);
                if(count($users) != count($user_ids)){
                    foreach($users as $user) {
                        $user['dept_name'] = $user['depname'];
                        $user['photo'] = '../../source/images/img_01.png';
                        $result[$user['PID']] = $user;
                        $returnIds[] = $user['PID'];
                    }
                    $delIds = array_diff($user_ids, $returnIds);
                    foreach($delIds as $id){
                        $result[$id] = self::delUserInfo($id);
                    }
                }else {
                    foreach($users as $user){
                        $user['dept_name'] = $user['depname'];
                        $user['photo'] = '../../source/images/img_01.png';
                        $result[$user['PID']] = $user;
                    }
                }
            }
        } else {
            if($user_ids){
                foreach($user_ids as $id){
                    $result[$id] = self::getInfo($id);
                }
            }
        }
        return $result;
    }

    public static function delUserInfo($id) {
        return array(
            'photo' => '../../source/images/img_01.png',
            'UserName' => '用户已删除',
            'dept_name' => '已删除',
            'corp_id' => 0,
            'LoginName' => '已删除',
            'Title' => '已删除',
        );
    }

}
