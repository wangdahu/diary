<?php
$title = "日志设置";
$setDefault = 'diary';
$uid = $diary->uid;
include dirname(dirname(__FILE__))."/class/DiarySet.php";
if(isset($_POST['working_time'])){
    DiarySet::saveWorkingTime($diary, $_POST['working_time']);
    // 设置循环
    DiaryLoop::insertPolling(0, 'sendRemind');
    DiaryLoop::insertPolling(1, 'sendReport');
}

$list = array('1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六', '7' => '周日');
$diarySetSql = "select `working_time` from `diary_set` where `uid` = $uid";

$selected = DiarySet::workingTime($diary, $diary->uid);
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>
<form method="post" id="working-time-form">
    <div class="content">
        <div class="set_bar mb25">
            <!--日报设置开始-->
            <h2 class="pt25">工作时间设置 <span style="font-size: 12px; font-weight: normal;">将根据您的上班规律，自动配置好您的日报、提醒等功能</span></h2>
            <ul class="set_list">
                <li>
                    <p>
                        <?php foreach($list as $key => $val):?>
                        <label>
                            <input type="checkbox" name="working_time[]" class="checkall" <?php echo in_array($key, $selected) ? 'checked' : '';?> value="<?php echo $key;?>">
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                </li>
            </ul>
        </div>
        <div class="form-action">
            <button type="submit">确定</button>
            <button type="reset">取消</button>
        </div>
    </div>
</form>
<?php include "views/layouts/footer.php"; ?>
