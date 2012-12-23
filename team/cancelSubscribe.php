<?php
if($_POST){
    $typeStr = $_POST["type"];
    $uid = $_POST["uid"];
    $type = $typeStr == 'index' ? 1 : ($typeStr == 'week' ? 2 : 3);
    $sql = "delete from `diary_subscribe_object` where `from_uid` = $uid and `type` = $type";
    echo $diary->db->query($sql);
}
