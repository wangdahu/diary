<?php
class User{

    public static function getInfo($uid){
        return array(
            'photo' => '../../source/images/img_01.png',
            'username' => $uid,
            'dept_name' => $uid,
            'corp_id' => 1,
        );
    }
}
