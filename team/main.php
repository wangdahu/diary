<?php
$showObject = $forward < 0 ? false : true;
$users = DiaryUser::getUsers($teamShowObject);
$reportUsers = DiaryReport::getDateReports($diary, $type, $object);
$commentUsers = DiaryComment::getDateComments($diary, $type, $object);
$viewUsers = DiaryViewRecord::getDateViews($diary, $type, $object, $diary->uid);
?>
<?php if($showObject):?>
<div class="content">
    <div class="set_bar mb25">
        <!--标签设置开始-->
        <ul class="dy clearfix">
            <?php foreach($teamShowObject as $uid): ?>
            <?php
                 $user = $users[$uid];
                 $url = "daily?forward=$forward&uid=$uid";
                 if($setDefault === 'week'){
                     $url = "weekly?forward=$forward&uid=$uid";
                 }elseif($setDefault === 'month'){
                     $url = "monthly?forward=$forward&uid=$uid";
                 }
                 $wiseucUrl = "wisetong://message/?uid=".$user['LoginName']."&myid=".$diary->LoginName;
                 $isReport = in_array($uid, $reportUsers);
                 $isr = $isReport ? "mini-report" : "mini-unreport";
                 $reportStr = $isReport ? "已汇报" : "未汇报";
                 if(in_array($uid, $viewUsers) || !$isReport) {
                     $cls = '';
                 }else {
                     $cls = 'unread';
                 }
                 if(in_array($uid, $commentUsers)) {
                     $cls .= ' has-comment';
                 }
            ?>
            <li data-url="<?php echo $url;?>" class="js-href clearfix <?php echo $cls; ?>" style="cursor: pointer;">
                <div class="pic">
                    <a href="<?php echo $wiseucUrl;?>" class="wiseuc-url">
                     <img style="height: 56px; width: 56px;" class="wiseuc-url" src="<?php echo $user['photo']; ?>" />
                     </a>
                </div>
                <div class="info ellipsis" style="width: 170px;">
                    <a href="<?php echo $wiseucUrl;?>" class="wiseuc-url"><?php echo $user['UserName']; ?></a>（<?php echo $user['dept_name']; ?>-<?php echo $user['Title'];?>）
                    <div class="<?php echo $isr; ?>"><?php echo $reportStr;?></div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<?php endif;?>
<script>
    $(function() {
        $('.js-href').click(function(e) {
            var target = $(e.target),
            url = $(this).data('url');
            if(!target.is('.wiseuc-url')) {
                location.href = url;
            }
        });
    });
</script>
