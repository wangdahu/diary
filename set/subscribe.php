<?php
$title = "订阅设置";
$setDefault = 'subscribe';

if($_POST){
    $daily_users = explode(',', $_POST['daily_user_object']);
    $daily_depts = explode(',', $_POST['daily_dept_object']);
    $weekly_users = explode(',', $_POST['weekly_user_object']);
    $weekly_depts = explode(',', $_POST['weekly_dept_object']);
    $monthly_users = explode(',', $_POST['monthly_user_object']);
    $monthly_depts = explode(',', $_POST['monthly_dept_object']);
    // 设置订阅对象
    DiarySet::saveSubscribeObject($diary, $daily_users, $daily_depts, $weekly_users, $weekly_depts, $monthly_users, $monthly_depts);
}

$subscribeObject = DiarySet::subscribeObject($diary, $diary->uid);
$subscribeStr = DiarySet::getNameAndDeptStr($subscribeObject);
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>


<div class="content">
    <form id="report-set-form" method="post">
        <div class="set_bar mb25">
            <div class="pt25">
                <div class="hint pl10">在此订阅他人的日志，系统将自动将他们的报告汇总到您的“团队日志”。</div>
            </div>
            <!--日报设置开始-->
            <h2 class="pt25">日报</h2>
            <ul class="set_list">
                <li>
                    <label><a href="javascript:;" class="opentag btn_6">选择汇报对象</a></label>
                    <p>
                        <textarea readonly name="daily" id="daily" class="set_textarea"><?php echo $subscribeStr['daily_str'];?></textarea>
                    </p>
                </li>
                <input type="hidden" name="daily_user_object" id="daily_user_object" value="<?php echo implode(',', $subscribeObject['daily_object']['user']); ?>"/>
                <input type="hidden" name="daily_dept_object" id="daily_dept_object" value="<?php echo implode(',', $subscribeObject['daily_object']['dept']); ?>"/>
            </ul>
            <!--日报设置结束-->
            <!--周报设置开始-->
            <h2>周报</h2>
            <ul class="set_list">
                <li>
                    <label><a href="javascript:;" class="opentag btn_6">选择汇报对象</a></label>
                    <p>
                        <textarea readonly name="weekly" id="weekly" class="set_textarea"><?php echo $subscribeStr['weekly_str'];?></textarea>
                    </p>
                </li>
                <input type="hidden" name="weekly_user_object" id="weekly_user_object" value="<?php echo implode(',', $subscribeObject['weekly_object']['user']); ?>"/>
                <input type="hidden" name="weekly_dept_object" id="weekly_dept_object" value="<?php echo implode(',', $subscribeObject['weekly_object']['dept']); ?>"/>
            </ul>
            <!--周报设置结束-->
            <!--月报设置开始-->
            <h2>月报</h2>
            <ul class="set_list">
                <li>
                    <label><a href="javascript:;" class="opentag btn_6">选择汇报对象</a></label>
                    <p>
                        <textarea readonly name="monthly" id="monthly" class="set_textarea"><?php echo $subscribeStr['monthly_str'];?></textarea>
                    </p>
                </li>
                <input type="hidden" name="monthly_user_object" id="monthly_user_object" value="<?php echo implode(',', $subscribeObject['monthly_object']['user']); ?>"/>
                <input type="hidden" name="monthly_dept_object" id="monthly_dept_object" value="<?php echo implode(',', $subscribeObject['monthly_object']['dept']); ?>"/>
            </ul>
            <!--月报设置结束-->
            <h2 class="mb10"></h2>
            <div class="form-action">
                <button type="submit">确定</button>
                <button type="reset">取消</button>
            </div>
        </div>
    </form>
</div>
<?php include "views/layouts/footer.php"; ?>
<script>
    $(function(){
        $('.set_textarea').change(function(){
            $("#" + this.id + "_object").val(this.value);
        });
    });
</script>


<?php include "plugins.php"; ?>
