<?php if(!(isset($_COOKIE['setReportRemind']) && $_COOKIE['setReportRemind'] == 'no') && !$diary->isSetReport): ?>
<div id="showRemindReport" style="background: #fff2c2; margin-top: -18px; margin-bottom: 20px; height: 30px; line-height: 30px; text-align: center; font-size: 14px; font-family: 宋体"><a id="no-remind" style="margin-right: 10px; float: right;" href="javascript:"><strong>不再提醒</strong></a>尽快完善您的日志设置，一切将更轻松！<a style="font-size: 16px; padding-left: 20px;" href="/diary/index.php/set/index"><strong>现在就去</strong></a></div>
<?php endif; ?>
<script>
    $('#no-remind').click(function() {
        var exp = new Date(),
        name = 'setReportRemind',
        value = 'no';
        exp.setTime(exp.getTime() + 86400 * 300000);//过期时间 300天
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/;";
        $('#showRemindReport').hide();
    });
</script>
