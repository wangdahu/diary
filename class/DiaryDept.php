<?php
class DiaryDept{

    public static function getInfo($dept_id){
        return array(
            'name' => $dept_id,
            'corp_id' => 1,
        );
    }
}
