<?php
$title = "日报-团队日志";
$type = 'daily';
$weekarray = array("日","一","二","三","四","五","六");

// 向前向后翻天
$forward = isset($_GET['forward']) ? (int)$_GET['forward'] : 0;
if(!$forward) {
    $startTime = isset($_GET['startTime']) ? (int)$_GET['startTime'] : 0;
    if($startTime) {
        $forward = floor((strtotime('today') - $startTime)/86400);
    }
}
$showDiary = $forward < 0 ? false : true;
if($forward){
    $forwardDays = $forward + 1;
    $backwardDays = $forward - 1;
    $currentDate = date('Y-m-d',time() - $forward*86400);
}else{
    $forwardDays = 1;
    $backwardDays = -1;
    $currentDate = date('Y-m-d',time());
}
$startTime = strtotime($currentDate);
$endTime = $startTime + 86400 - 1;
$object = date('Y-m-d', $endTime);
$uid = (int) $_GET['uid'];

$showCommit = false;
$allowPay = false;
$isReported = true;
// 判断是否为补交/未汇报/已汇报
if($forward == 0) { // 本日
    // 是否已过汇报时间
    $reportTime = DiarySet::reportTime($diary, $uid);
    $dailyTime = $reportTime['dailyReport']['hour'].":".$reportTime['dailyReport']['minute'];
    $isReported = $allowPay = false;
    if(time() > strtotime($object." ".$dailyTime)) { // 已过汇报时间
        $showCommit = true;
        $isReported = DiaryReport::checkReport($diary, $type, $object, $uid);
    }else {
        $isReported = false;
    }
}else { // 过去
    $isReported = DiaryReport::checkReport($diary, $type, $object, $uid);
    $showCommit = true;
}
$forwardTitle = '日报';
?>

<?php include "views/layouts/header.php"; ?>
<?php include "views/layouts/diary-header.php"; ?>
<?php include "views/team/view-top.php"; ?>
<?php if($forward >= 0):?>
<?php
// 查看的日期
$object = date('Y-m-d', $endTime);

$user = DiaryUser::getInfo($uid);
$corpId = $user['corp_id'];

$dailys = array();
$num = 0;
if($isReported) {
    // 该企业该用户在选择时间内的日报
    $rowsSql = "select * from `diary_info` where `uid` = $uid and `corp_id` = $corpId and `type` = 1 and `show_time` between $startTime and $endTime order by id desc";
    $result = $diary->db->query($rowsSql);
    while($row = $result->fetch_array(MYSQLI_ASSOC)){
        $dailys[] = $row;
    };
    $num = count($dailys);
}
$myselfLogin = URLEncode(Base64_encode($diary->LoginName));
$userLogin = URLEncode(Base64_encode($user['LoginName']));
$wiseucUrl = "wisetong://message/?uid=".$userLogin."&myid=".$myselfLogin;

// 日报汇报给我的和我订阅的用户
$teamShowObject = DiarySet::teamShowObject($diary, 1);
$showUsers = DiaryUser::getUsers($teamShowObject);
?>

<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
    <a href="<?php echo $backUrl; ?>" class="fl btn_back mr10"></a>
        <h2 class="content_tit clearfix user-info">
            <a href="<?php echo $wiseucUrl;?>"><?php echo $user['UserName'];?></a><?php echo "（".$user['dept_name']."-".$user['Title']."）";?>
            <select class="js-viewOther">
                <option data-href="javascript:">请选择</option>
                <?php foreach($showUsers as $sid => $u):?>
                <?php $url = "daily?forward=".$forward."&uid=".$sid;?>
                <option data-href="<?php echo $url;?>"><?php echo $u['UserName']." (".$u['dept_name']."-".$u['Title'].")"?></option>
                <?php endforeach;?>
            </select>
        </h2>
       <h2 class="content_tit clearfix">
            <p>今日工作：<em><?php echo $num;?> 项</em></p>
        </h2>
        <?php if(!$num):?>
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
        <div class="c_b"></div>
        <?php else:?>
        <?php foreach($dailys as $daily):?>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <?php if($isReported):?>
                <div>
                    <p><?php echo nl2br($daily['content']); ?></p>
                </div>
                <?php else:?>
                <div data-daily_id="<?php echo $daily['id']; ?>" class="js-edit_diary" style="cursor: pointer">
                    <p><?php echo nl2br($daily['content']); ?></p>
                    <div style="display:none;"><?php echo $daily['content'];?></div>
                </div>
                <?php endif;?>
                <div class="clearfix diary-operation" >
                    <span class="daily-date"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
    <?php
        $tagList = array();
        $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
        $tagIds = array_keys($tagList);
    ?>
                    <span class="tag-list">
                        <?php foreach($tagList as $tag):?>
                            <div style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>;">
                                <div title="<?php echo $tag['tag']?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                    <?php $url = "/diary/index.php/team/tagDaily?tag=".$tag['id']."&uid=".$uid; ?>
                                    <a style="text-decoration: none;" href="<?php echo $url;?>">
                                        <span style="margin:4px;"><?php echo $tag['tag']?></span>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </span>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
    <!--今日工作结束-->
    <?php include "comment.php"; ?>
</div>
<?php else:?>
<?php include "views/team/forward.php"; ?>
<?php endif;?>
<?php include "views/layouts/footer.php"; ?>
