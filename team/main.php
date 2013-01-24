<?php
$showObject = $forward < 0 ? false : true;
echo "<pre>"; var_dump($teamShowObject);
?>
<?php if($showObject):?>
<div class="content">
    <div class="set_bar mb25">
        <!--标签设置开始-->
        <ul class="dy clearfix">
            <?php foreach($teamShowObject as $uid): ?>
            <?php
                 $user = DiaryUser::getInfo($uid);
                 $url = "daily?forward=$forward&uid=$uid";
                 if($setDefault === 'week'){
                     $url = "weekly?forward=$forward&uid=$uid";
                 }elseif($setDefault === 'month'){
                     $url = "monthly?forward=$forward&uid=$uid";
                 }
                 $status = DiaryViewRecord::checkUser($diary, $type, $uid, $object);
                 $cls = $status ? '' : 'unread';
                 if(DiaryComment::checkUserObjectComment($diary, $uid, $type, $object)) {
                     $cls .= ' has-comment';
                 }
                 $isReport = DiaryReport::checkReport($diary, $type, $object, $uid);
                 $isr = $isReport ? "mini-report" : "mini-unreport";
                 $reportStr = $isReport ? "已汇报" : "未汇报";
            ?>
            <li class="clearfix <?php echo $cls; ?>">
                <div class="pic">
                    <a href="<?php echo $url;?>">
                     <img style="height: 56px; width: 56px;" src="<?php echo $user['photo']; ?>" />
                     </a>
                </div>
                <div class="info ellipsis" style="width: 170px;">
                    <a href="<?php echo $url;?>"><?php echo $user['UserName']; ?></a>（<?php echo $user['dept_name']; ?>-<?php echo $user['Title'];?>）
                    <div class="<?php echo $isr; ?>"><?php echo $reportStr;?></div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<?php endif;?>
