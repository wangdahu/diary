<?php
$user = DiaryUser::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 3 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$monthlys = array();
while($row = $result->fetch_assoc()){
    $monthlys = $row;
};

// 查看当前第一天和最后一天是星期几，补全最开始和结尾的格子
$maxDays = date('t', $startTime);
// 今天的日
$currentMonthDate = date('j');
if($maxDays < $currentMonthDate){
    $currentMonthDate = $maxDays;
}
// 当前月的今日的时间戳
$currentTime = $startTime + ($currentMonthDate - 1)*86400;
$currentWeekth = date('w', $currentTime); // 星期几

// 第一天是星期几，最后一天是星期几
$firstDayWeekth = date('w', $startTime);
$endDayWeekth = date('w', $endTime);
// 第一个格子的日期
if($firstDayWeekth == 0){ // 星期天
    $firstTime = $startTime - (7-1)*86400;
}else{
    $firstTime = $startTime - ($firstDayWeekth-1)*86400;
}
$currentWeek = floor(($currentTime - $firstTime)/(7*86400));
if($firstDayWeekth != 1){
    // 上月第一天的时间戳
    $currentMonthPrev = mktime(0, 0, 0, date("m")-$forward-1, 1, date("Y"));
    $prevMonth = date('m', $currentMonthPrev);
    // 计算上月最后一周
    $weekArr = array($prevMonth.'月末周', '第一周','第二周','第三周','第四周','第五周');
}else{
    $weekArr = array('第一周','第二周','第三周','第四周','第五周','第六周');
}
$maxWeek = ceil(($endTime - $firstTime)/(7*86400));
// 当前月历中的开始时间和结束时间
$lastTime = $firstTime + $maxWeek*7*86400 - 1;
// 当前月历中哪些天有评论
$firstDate = date('Y-m-d', $firstTime);
$lastDate = date('Y-m-d', $lastTime);

$dateObject = DiaryComment::getWhichDate($diary, $uid, 'daily', $firstDate, $lastDate);
$weekObject = DiaryComment::getWhichDate($diary, $uid, 'weekly', date('Y-W', strtotime($firstDate)), date('Y-W', strtotime($lastDate)));

// 查询有内容的天并未汇报
$reportDailys = DiaryDaily::getReportDailys($diary, $firstDate, $lastDate, $uid);
for($w = 0; $w < $maxWeek; $w++) {
    $weeks[] = date('Y-W', ($firstTime + 7*$w*86400));
}
$weekStr = '"'.implode('","', $weeks).'"';
$reportWeeklys = DiaryDaily::getReportWeeklys($diary, $weekStr, $uid);
$myselfLogin = URLEncode(Base64_encode($diary->LoginName));
$userLogin = URLEncode(Base64_encode($user['LoginName']));
$wiseucUrl = "wisetong://message/?uid=".$userLogin."&myid=".$myselfLogin;

$hasContentDates = DiaryDaily::getHasContentDates($diary, $firstTime, $lastTime, 1, $uid);
$hasContentWeeks = DiaryDaily::getHasContentDates($diary, $firstTime, $lastTime, 2, $uid);
?>
<div class="content">
    <!--本月总结开始-->
    <div class="content_bar mb25">
        <?php if(isset($from)):?>
        <a href="<?php echo $backUrl; ?>" class="fl btn_back mr10"></a>
        <h2 class="content_tit clearfix user-info">
            <a href="<?php echo $wiseucUrl;?>"><?php echo $user['UserName'];?></a><?php echo "（".$user['dept_name']."-".$user['Title']."）";?>
        </h2>
        <?php endif;?>
        <h2 class="content_tit clearfix">
            <p>月报</p>
            <?php if(!isset($from) && $allowPay):?>
            <?php if($monthlys):?>
            <a href="javascript:" class="fr pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!isset($from) && !$isReported):?>
            <a href="javascript:;" class="fr write-monthly"></a>
            <?php endif;?>
        </h2>

        <?php if(!$monthlys || (isset($from) && !$isReported)):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <?php else:?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-monthly_id="<?php echo $monthlys['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($monthlys['content']); ?></p>
                    <div style="display:none;"><?php echo $monthlys['content'];?></div>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif;?>
        <div class="c_b"></div>
    </div>
    <!--本月总结结束-->
    <!--日历开始-->
    <div style="padding: 0 10px;">
        <div class="c_t mt10"></div>
        <div class="c_c">
            <table class="calendar">
                <thead>
                    <tr>
                        <td style="color: black; font-size: 20px;"><?php echo date('Y年m月', $startTime);?></td>
                        <td style="<?php echo $currentWeekth == 1 ? 'color:black;' : ''?>">星期一</td>
                        <td style="<?php echo $currentWeekth == 2 ? 'color:black;' : ''?>">星期二</td>
                        <td style="<?php echo $currentWeekth == 3 ? 'color:black;' : ''?>">星期三</td>
                        <td style="<?php echo $currentWeekth == 4 ? 'color:black;' : ''?>">星期四</td>
                        <td style="<?php echo $currentWeekth == 5 ? 'color:black;' : ''?>">星期五</td>
                        <td style="<?php echo $currentWeekth == 6 ? 'color:black;' : ''?>">星期六</td>
                        <td style="<?php echo $currentWeekth == 0 ? 'color:black;' : ''?>">星期日</td>
                    </tr>
                </thead>
                <tbody>
                    <?php for($w = 0; $w < $maxWeek; $w++):?>
                    <tr>
                        <?php
                             $thisWeek = date('Y-W',($firstTime + 7*$w*86400));
                             $weekForward = DiaryDaily::getForward($firstTime + 7*$w*86400, 2);
                             $url = "/diary/index.php/my/weekly?forward=".$weekForward;
                             if(isset($from)) {
                                $url = "/diary/index.php/team/weekly?forward=".$weekForward."&uid=".$uid;
                             }
                             $isFuture = ($firstTime + 7*$w*86400) > time();
                             $noReport = !in_array($thisWeek, $reportWeeklys) && !$isFuture;
                             $hasContent = in_array($thisWeek, $hasContentWeeks);
                             $hasComment = in_array($thisWeek, $weekObject);
                        ?>
                        <td class="js-hover <?php echo $isFuture ? 'td_white' : '';?>" style="border:1px solid #aaa; width: 134px;" >
                                <div class="icon-wrapper">
                                    <?php if($noReport):?>
                                    <span class="unreport"></span>
                                    <?php endif;?>
                                    <?php if($noReport && $hasContent):?>
                                    <span class="has-content"></span>
                                    <?php endif;?>
                                    <?php if($hasComment):?>
                                    <span class="comments"></span>
                                    <?php endif;?>
                                    <a style="width: 100%; top:50%; margin-top: -9px; left: 0; font: 14px 微软雅黑; color: <?php echo $currentWeek >= $w ? '#000;' : '#9c9c9c;'?>" href="<?php echo $url;?>"><?php echo $weekArr[$w]; ?></a>
                                </div>

                        </td>
                        <?php for($i = 0; $i < 7; $i++): ?>
                              <?php
                                   $thisTime = $firstTime + 7*$w*86400 + $i*86400;
                                   $j = date('j', $thisTime);
                                   $thisColor = '';
                                   if($currentWeek == $w && $j == $currentMonthDate) {
                                       $thisColor = "color: #fff;";
                                   }else {
                                       if($thisTime > $currentTime) {
                                           $thisColor = "color: #9c9c9c;";
                                       }
                                   }
                                   $thisDate = date('Y-m-d', $thisTime);
                                   $dateForward = DiaryDaily::getForward($firstTime + 7*$w*86400 + $i*86400);
                                   $url = "/diary/index.php/my/index?forward=".$dateForward;
                                   if(isset($from)) {
                                       $url = "/diary/index.php/team/daily?forward=".$dateForward."&uid=".$uid;
                                   }
                                   $noReport = !in_array($thisDate, $reportDailys) && $thisTime < time();
                                   $hasContent = in_array($thisDate, $hasContentDates);
                                   $hasComment = in_array($thisDate, $dateObject);
                               ?>
                            <td class="js-hover <?php echo ($currentWeek == $w && $j == $currentMonthDate) ? 'td_blue' : ''; ?> <?php echo $thisTime > time() ? 'td_white' : '';?>" >
                                <div class="icon-wrapper">
                                    <?php if($noReport):?>
                                    <span class="unreport"></span>
                                    <?php endif;?>
                                    <?php if($hasComment):?>
                                    <span class="comments"></span>
                                    <?php endif;?>
                                    <?php if($noReport && $hasContent):?>
                                    <span class="has-content" style="top: 0; left: 0;"></span>
                                    <?php endif;?>
                                    <a href="<?php echo $url;?>" style="width: 100%; top:50%; left:0; margin-top: -13px;font: 20px Arial; <?php echo $thisColor;?>"><?php echo $j;?></a>
                                </div>
                        </td>
                        <?php endfor;?>
                    </tr>
                    <?php endfor;?>
                </tbody>
            </table>
        </div>
        <div class="c_b"></div>
    </div>
    <!--日历结束-->
    <?php if($showCommit):?>
    <?php include dirname(dirname(dirname(__FILE__)))."/team/comment.php"; ?>
    <?php endif;?>
</div>
<script>
    $(function() {
        $('.js-hover').mouseover(function() {
            $(this).find('div').addClass('hover');
            $(this).find('a').addClass('hover');
        }).mouseout(function() {
            $(this).find('div').removeClass('hover');
            $(this).find('a').removeClass('hover');
        });
    });
</script>
