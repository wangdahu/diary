<?php
$title = "提醒设置";
$setDefault = 'remind';

include dirname(dirname(__FILE__))."/class/Set.php";
if($_POST){
    Set::saveRemindTime($diary, $_POST['dailyRemind'], $_POST['weeklyRemind'], $_POST['monthlyRemind']);
}

$hours = range(0, 24);
$minutes = range(0, 60, 5);
$months = range(1, 28);
$ways = array('email'=>'邮件', 'sms'=>'短信', 'mms'=>'彩信', 'remind'=>'汇讯提醒');

// 获取提醒设置
$remindSet = Set::remindTime($diary);
// echo "<pre>"; var_dump($remindSet);exit;
$weeks = array('1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六', '7' => '周日');

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>
<div class="content">
    <form id="report-set-form" method="post">
        <div class="set_bar mb25">
            <!--日报设置开始-->
            <h2 class="pt25">日报</h2>
            <ul class="set_list">
                <li>
                    <label>汇报时间</label>
                    <select name="dailyRemind[hour]">
                        <?php foreach($hours as $hour):?>
                        <option <?php echo $remindSet['dailyRemind']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                            <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                        </option>
                        <?php endforeach;?>
                    </select>
                    <select name="dailyRemind[minute]">
                        <?php foreach($minutes as $minute):?>
                        <option <?php echo $remindSet['dailyRemind']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
                            <?php echo str_pad($minute, 2, 0, STR_PAD_LEFT);?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </li>
                <li>
                    <label>汇报方式</label>
                    <p>
                        <?php foreach($ways as $key => $val):?>
                        <label>
                            <input type="checkbox" name="dailyRemind[way][]" class="checkall" <?php echo in_array($key, $remindSet['dailyRemind']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="dailyRemind[way][]" value="remind"/>
                    </p>
                </li>
            </ul>
            <!--日报设置结束-->
            <!--周报设置开始-->
            <h2>周报</h2>
            <ul class="set_list">
                <li>
                    <label>汇报时间</label>
                    <p>
                        <?php foreach($weeks as $key => $val):?>
                        <label>
                            <input type="radio" name="weeklyRemind[w]" class="checkall" <?php echo $key == $remindSet['weeklyRemind']['w'] ? checked : ''; ?> value="<?php echo $key;?>" >
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <select name="weeklyRemind[hour]">
                            <?php foreach($hours as $hour):?>
                            <option <?php echo $remindSet['weeklyRemind']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                                <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <select name="weeklyRemind[minute]">
                            <?php foreach($minutes as $minute):?>
                            <option <?php echo $remindSet['weeklyRemind']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
                                <?php echo str_pad($minute, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </p>
                </li>
                <li>
                    <label>汇报方式</label>
                    <p>
                        <?php foreach($ways as $key => $val):?>
                        <label>
                            <input type="checkbox" name="weeklyRemind[way][]" class="checkall" <?php echo in_array($key, $remindSet['weeklyRemind']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="weeklyRemind[way][]" value="remind"/>
                    </p>
                </li>
            </ul>
            <!--周报设置结束-->
            <!--月报设置开始-->
            <h2>月报</h2>
            <ul class="set_list">
                <li>
                    <label>汇报时间</label>
                    <p>
                        <select name="monthlyRemind[date]">
                            <?php foreach($months as $month):?>
                            <option <?php echo $remindSet['monthlyRemind']['date'] == $month ? selected : ''; ?> value="<?php echo $month?>">
                                <?php echo str_pad($month, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>日
                        <select name="monthlyRemind[hour]">
                            <?php foreach($hours as $hour):?>
                            <option <?php echo $remindSet['monthlyRemind']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                                <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <select name="monthlyRemind[minute]">
                            <?php foreach($minutes as $minute):?>
                            <option <?php echo $remindSet['monthlyRemind']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
                                <?php echo str_pad($minute, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                    </p>
                </li>
                <li>
                    <label>汇报方式</label>
                    <p>
                        <?php foreach($ways as $key => $val):?>
                        <label>
                            <input type="checkbox" name="monthlyRemind[way][]" class="checkall" <?php echo in_array($key, $remindSet['monthlyRemind']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="monthlyRemind[way][]" value="remind"/>
                    </p>
                </li>
            </ul>
            <!--月报设置结束-->
        </div>
        <div class="form-action">
            <button type="submit">确定</button>
            <button type="reset">取消</button>
        </div>
    </form>
</div>
<?php include "views/layouts/footer.php"; ?>
