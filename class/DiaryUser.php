<?php
class DiaryUser{

    public static function getInfo($uid){
        return array(
            'photo' => '../../source/images/img_01.png',
            'username' => $uid,
            'dept_name' => '技术部',
            'corp_id' => 1,
        );
    }
}
