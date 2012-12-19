<?php
$title = "汇报设置";
$setDefault = 'report';
include dirname(dirname(__FILE__))."/class/Set.php";
if($_POST){
    Set::saveReportTime($diary, $_POST['dailyReport'], $_POST['weeklyReport'], $_POST['monthlyReport']);
}

$hours = range(0, 24);
$minutes = range(0, 60, 5);
$months = range(1, 28);
$ways = array('email'=>'邮件', 'sms'=>'短信', 'mms'=>'彩信', 'remind'=>'汇讯提醒');
// 获取汇报设置
$reportSet = Set::reportTime($diary);
// echo "<pre>"; var_dump($reportSet);exit;
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
                    <label><a href="#">选择汇报对象</a></label>
                    <p><textarea name="" id="" class="set_textarea"></textarea></p>
                </li>
                <li>
                    <label>汇报时间</label>
                    <select name="dailyReport[hour]">
                        <?php foreach($hours as $hour):?>
                        <option <?php echo $reportSet['dailyReport']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                            <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                        </option>
                        <?php endforeach;?>
                    </select>
                    <select name="dailyReport[minute]">
                        <?php foreach($minutes as $minute):?>
                        <option <?php echo $reportSet['dailyReport']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
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
                            <input type="checkbox" name="dailyReport[way][]" class="checkall" <?php echo in_array($key, $reportSet['dailyReport']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="dailyReport[way][]" value="remind"/>
                    </p>
                </li>
            </ul>
            <!--日报设置结束-->
            <!--周报设置开始-->
            <h2>周报</h2>
            <ul class="set_list">
                <li>
                    <label><a href="#">选择汇报对象</a></label>
                    <p><textarea name="" id="" class="set_textarea"></textarea></p>
                </li>
                <li>
                    <label>汇报时间</label>
                    <p>
                        <?php foreach($weeks as $key => $val):?>
                        <label>
                            <input type="radio" name="weeklyReport[w]" class="checkall" <?php echo $key == $reportSet['weeklyReport']['w'] ? checked : ''; ?> value="<?php echo $key;?>" >
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <select name="weeklyReport[hour]">
                            <?php foreach($hours as $hour):?>
                            <option <?php echo $reportSet['weeklyReport']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                                <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <select name="weeklyReport[minute]">
                            <?php foreach($minutes as $minute):?>
                            <option <?php echo $reportSet['weeklyReport']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
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
                            <input type="checkbox" name="weeklyReport[way]" class="checkall" <?php echo in_array($key, $reportSet['weeklyReport']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="weeklyReport[way][]" value="remind"/>
                    </p>
                </li>
            </ul>
            <!--周报设置结束-->
            <!--月报设置开始-->
            <h2>月报</h2>
            <ul class="set_list">
                <li>
                    <label><a href="#">选择汇报对象</a></label>
                    <p><textarea name="" id="" class="set_textarea"></textarea></p>
                </li>
                <li>
                    <label>汇报时间</label>
                    <p>
                        <select name="monthlyReport[date]">
                            <?php foreach($months as $month):?>
                            <option <?php echo $reportSet['monthlyReport']['date'] == $month ? selected : ''; ?> value="<?php echo $month?>">
                                <?php echo str_pad($month, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>日
                        <select name="monthlyReport[hour]">
                            <?php foreach($hours as $hour):?>
                            <option <?php echo $reportSet['monthlyReport']['hour'] == $hour ? selected : ''; ?> value="<?php echo $hour; ?>">
                                <?php echo str_pad($hour, 2, 0, STR_PAD_LEFT);?>
                            </option>
                            <?php endforeach;?>
                        </select>
                        <select name="monthlyReport[minute]">
                            <?php foreach($minutes as $minute):?>
                            <option <?php echo $reportSet['monthlyReport']['minute'] == $minute ? selected : ''; ?> value="<?php echo $minute?>">
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
                            <input type="checkbox" name="monthlyReport[way][]" class="checkall" <?php echo in_array($key, $reportSet['monthlyReport']['way']) ? checked : ''; ?> value="<?php echo $key;?>" <?php echo $key == 'remind' ? 'disabled' : ''?>>
                            <?php echo $val; ?>
                        </label>
                        <?php endforeach;?>
                        <input type="hidden" name="monthlyReport[way][]" value="remind"/>
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
