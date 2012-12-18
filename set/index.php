<?php
$title = "汇报设置";
$setDefault = 'report';

$hours = range(0, 24);
$minutes = range(0, 60, 5);
$ways = array('email'=>'邮件', 'sms'=>'短信', 'mms'=>'彩信', 'remind'=>'汇讯提醒');
// 获取汇报设置
include dirname(dirname(__FILE__))."/class/Set.php";
$reportSet = Set::reportTime($diary);

?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>
<div class="content">
    <div class="set_bar mb25">
        <!--日报设置开始-->
        <h2 class="pt25">日报</h2>
        <ul class="set_list">
            <li>
                <label for=""><a href="#">选择汇报对象</a></label>
                <p><textarea name="" id="" class="set_textarea"></textarea></p>
            </li>
            <li>
                <label for="">汇报时间</label>
                <select name="subscribe_hour">
                    <?php foreach($hours as $hour):?>
                    <option <?php echo $reportSet['dailyReport']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                        <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                    </option>
                    <?php endforeach;?>
                </select>
                <select name="subscribe_minute">
                    <?php foreach($minutes as $minute):?>
                    <option <?php echo $reportSet['dailyReport']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
                        <?php echo str_pad($minute, 2, 0, STR_PAD_LEFT);?>
                    </option>
                    <?php endforeach;?>
                </select>
            </li>
            <li>
                <label for="">汇报方式</label>
                <p>
                    <?php foreach($ways as $key => $val):?>
                    <label>
                        <input type="checkbox" name="daily_way[]" class="checkall" <?php echo in_array($key, $reportSet['dailyReportRemindWay']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                        <?php echo $val; ?>
                    </label>
                    <?php endforeach;?>
                </p>
            </li>
        </ul>
        <!--日报设置结束-->
        <!--周报设置开始-->
        <h2>周报</h2>
        <ul class="set_list">
            <li>
                <label for=""><a href="#">选择汇报对象</a></label>
                <p><textarea name="" id="" class="set_textarea"></textarea></p>
            </li>
            <li>
                <label for="">汇报时间</label>
                <p><span><input type="radio" name="" id="" /> 周一</span><span><input type="radio" name="" id="" /> 周二</span><span><input type="radio" name="" id="" /> 周三</span><span><input type="radio" name="" id="" /> 周四</span><span><input type="radio" name="" id="" /> 周五</span><span><input type="radio" name="" id="" /> 周六</span><span><input type="radio" name="" id="" /> 周日</span><img src="images/img_02.png" alt="" /></p>
            </li>
            <li>
                <label for="">汇报方式</label>
                <p><span><input type="checkbox" class="checkall"> 邮件</span><span><input type="checkbox" class="checkall"> 短信</span><span><input type="checkbox" class="checkall"> 彩信</span><span><input type="checkbox" class="checkall"> 汇讯提醒</span></p>
            </li>
        </ul>
        <!--周报设置结束-->
        <!--月报设置开始-->
        <h2>月报</h2>
        <ul class="set_list">
            <li>
                <label for=""><a href="#">选择汇报对象</a></label>
                <p><textarea name="" id="" class="set_textarea"></textarea></p>
            </li>
            <li>
                <label for="">汇报时间</label>
                <p><span><select name="" id="">
                            <option value="">10</option>
                        </select>日</span><img src="images/img_02.png" alt="" /></p>
            </li>
            <li>
                <label for="">汇报方式</label>
                <p><span><input type="checkbox" class="checkall"> 邮件</span><span><input type="checkbox" class="checkall"> 短信</span><span><input type="checkbox" class="checkall"> 彩信</span><span><input type="checkbox" class="checkall"> 汇讯提醒</span></p>
            </li>
        </ul>
        <!--月报设置结束-->
    </div>
</div>
<?php include "views/layouts/footer.php"; ?>
