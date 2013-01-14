<?php
class DiarySms{

    /*
     *@param string $receive 接收人登录账户
     *@param string $title 标题
     *@param string $content 内容
     *@param string $url 自定义url地址
     *@param string $keycode 加密串
     *@return int $style 发送方式 （1单人发送）
     * */
    public static function sendSms($user_ids, $title, $content, $url) {
        $config = Diary::getConfig(); // 网站的基本配置
        $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();

        $users = DiaryUser::base($user_ids);
        foreach($users as $user) {
            $sms = array(
                'sendname' => $_session_arr['userInfo']['LoginName'],
                'receive' => $user['LoginName'],
                'title' => $title,
                'content' => $content,
                'url' => $url,
            );
            $result = $soap->doAct('getdepinfo', json_encode($sms));
        }
    }
}
