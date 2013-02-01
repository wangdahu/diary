<?php
class DiaryMsg{

    /*
     *@param string $receive 接收人登录账户
     *@param string $title 标题
     *@param string $content 内容
     *@param string $url 自定义url地址
     *@param string $keycode 加密串
     *@return int $style 发送方式 （1单人发送）
     * */
    public static function send($user_ids, $title, $content, $url) {
        $config = Diary::getConfig(); // 网站的基本配置
        $host = "http://".$config['host']."/Interface/www/op/stdserver.php?wsdl";
        $soap = new soapClient($host);
        $_session_arr = Session::instance()->get();

        $users = DiaryUser::base($user_ids);
        if($users) {
            foreach($users as $user) {
                $receive[] = $user['LoginName'];
            }
            $msg = array(
                'sendname' => $_session_arr['userInfo']['LoginName'], // 发送人登陆名
                'receive' => $receive, // 接受者id
                'title' => $title, // 标题
                'content' => $content, // 内容
                'url' => $url, // 地址
                'keycode' => $config['keyCode'],  // 验证码
                'style' => 1,  // 验证码
                'showtype' => 'weblog',
                'opttype' => 'views',
            );
            try {
                $_arr = json_encode($msg);

                $result = $soap->doAct('sendmsgs', $_arr);
                $_msg = json_decode($result, true);
                echo "<pre>"; var_dump($_msg);exit;
                $_msg_arr = json_decode($_msg['msg'], true);
            } catch(Exception $e) {
                var_dump($e);exit();
            }
        }
    }
}
