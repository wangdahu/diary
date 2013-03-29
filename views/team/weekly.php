<?php
$user = DiaryUser::getInfo($uid);
$corpId = $user['corp_id'];

// 该企业该用户在选择时间内的月报
$rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 2 and `show_time` between $startTime and $endTime";
$result = $diary->db->query($rowsSql);
$weeklys = array();
while($row = $result->fetch_assoc()){
    $weeklys = $row;
};

$myselfLogin = URLEncode(Base64_encode($diary->LoginName));
$userLogin = URLEncode(Base64_encode($user['LoginName']));
$wiseucUrl = "wisetong://message/?uid=".$userLogin."&myid=".$myselfLogin;

// 日报汇报给我的和我订阅的用户
$teamShowObject = DiarySet::teamShowObject($diary, 2);
$showUsers = DiaryUser::getUsers($teamShowObject);
?>
<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <?php if(isset($from)):?>
        <a href="<?php echo $backUrl; ?>" class="fl btn_back mr10"></a>
        <h2 class="content_tit clearfix user-info">  <a href="<?php echo $wiseucUrl;?>"><?php echo $user['UserName'];?></a><?php echo "（".$user['dept_name']."-".$user['Title']."）";?>
            <select class="js-viewOther">
                <option data-href="javascript:">请选择</option>
                <?php foreach($showUsers as $sid => $u):?>
                <?php $url = "weekly?forward=".$forward."&uid=".$sid;?>
                <option data-href="<?php echo $url;?>"><?php echo $u['UserName']." (".$u['dept_name']."-".$u['Title'].")"?></option>
                <?php endforeach;?>
            </select>
        </h2>
        <?php endif;?>
        <h2 class="content_tit clearfix">
            <p>周报</p>
            <?php if(!isset($from) && $allowPay):?>
            <?php if($weeklys):?>
            <a href="javascript:" class="fr pay-diary js-pay_diary"></a>
            <?php else:?>
            <a class="fr pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!isset($from) && !$isReported):?>
            <a href="javascript:" class="write-<?php echo $type?> fr"></a>
            <?php endif;?>
        </h2>

        <?php if(!$weeklys || (isset($from) && !$isReported)):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <?php if(isset($from) && !$isReported):?>
            <div class="c_c_c">
            <?php else:?>
            <div class="c_c_c no-content-daily" style="cursor: pointer;">
            <?php endif;?>
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未填写任何日志内容</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php else:?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                </div>
                <?php else:?>
                <div data-weekly_id="<?php echo $weeklys['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($weeklys['content']); ?></p>
                    <script type="text/string"><?php echo $weeklys['content'];?></script>
                </div>
                <?php endif;?>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endif;?>

        <?php if($weekDate): ?>
        <h2 class="content_tit clearfix">
            <p>本周工作：<?php echo $dailyNum; ?>项</p>
        </h2>
        <?php
            $reportDailys = DiaryDaily::getReportDailys($diary, date('Y-m-d', $startTime), date('Y-m-d', $endTime), $uid);
            foreach($weekDate as $k => $v):
$date = date('Y-m-d', $dateForwards[$v]);
$today = date('Y-m-d');
if($date < $today) {
    $cls = 'top-unreport';
    $clsTitle = '未汇报';
    if(in_array($date, $reportDailys)) {
        $cls = 'top-reported';
        $clsTitle = '已汇报';
    }
}else {
    $cls = 'top-wait-report';
    $clsTitle = '等待汇报';
    if($date == $today) {
        if(in_array($date, $reportDailys)) {
            $cls = 'top-reported';
            $clsTitle = '已汇报';
        }else {
            // 是否已过汇报时间
            $reportTime = DiarySet::reportTime($diary);
            $dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
            if(time() > strtotime($object." ".$dailyTime)){ // 已过汇报时间
                $cls = 'top-unreport';
                $clsTitle = '未汇报';
            }
        }
    }
}

        ?>
        <div>
            <div class="c_t mt10"></div>
            <div class="c_c">
                <div>
                    <p style="line-height:30px;">
                        <?php
                             $dateForward = DiaryDaily::getForward($dateForwards[$v]);
                             $url = "/diary/index.php/my/index?forward=".$dateForward;
                             if(isset($from)) {
                                 $url = "/diary/index.php/team/daily?forward=".$dateForward."&uid=".$uid;
                             }
                        ?>
                        <div class="js-unfold" href="javascript:" style=" line-height: 30px; cursor: pointer; text-decoration: none; color: #3f3f3f;">
                        <a href="<?php echo $url;?>" class="a_01 fr">进入</a>
                            <strong><span class="js-hide" title="点击收起" style="display: none;"> ^ </span><span class="js-show" title="点击展开" > > </span><?php echo "周".$k." ".$v; ?></strong>
                            <span>工作：<?php echo isset($dailys[$v]) ? count($dailys[$v]) : 0;?>项</span>
                            <span style="padding: 5px 0 5px 43px;"  class="status <?php echo $cls?>"><?php echo $clsTitle; ?></span>
                        </div>
                    </p>
                </div>
                <div class="js-daily" style="display: none;">
                <?php if(isset($dailys[$v])): foreach($dailys[$v] as $date => $daily):?>
                <?php
                     $tagList = array();
                     $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
                     $tagIds = array_keys($tagList);
                ?>
                <div class="c_c_c "  style="padding: 10px 0;border-top: 1px solid #DADADA">
                    <div>
                        <p><?php echo nl2br($daily['content']);?></p>
                    </div>
                    <div class="clearfix diary-operation" >
                        <span class="daily-date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
                        <span class="tag-list">
                            <?php foreach($tagList as $tag):?>
                            <div style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>;">
                                <div title="<?php echo $tag['tag']?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                <?php $url = "/diary/index.php/my/tagDaily?tag=".$tag['id']; ?>
                                <a style="text-decoration: none;" href="<?php echo $url;?>">
                                    <span style="margin:4px;"><?php echo $tag['tag']?></span>
                                </a>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </span>
                    </div>
                </div>
                <?php endforeach; endif;?>
                </div>
            </div>
            <div class="c_b"></div>
        </div>

        <?php endforeach; endif;?>
    </div>
    <!--今日工作结束-->
    <?php if($showCommit):
          include dirname(dirname(dirname(__FILE__)))."/team/comment.php";
    endif;?>
</div>
<script>
    $(function() {
        $('.js-unfold').click(function() {
            $(this).find('.js-show').toggle();
            $(this).find('.js-hide').toggle();
            $(this).closest('.c_c').find('.js-daily').toggle();
        });
    });
</script>
