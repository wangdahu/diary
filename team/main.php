<?php
$showObject = $forward < 0 ? false : true;
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
            ?>
            <li class="clearfix <?php echo $cls; ?>">
                <div class="pic">
                    <a href="<?php echo $url;?>">
                        <img src="<?php echo $user['photo']; ?>" />
                     </a>
                </div>
                <div class="info">
                    <a href="<?php echo $url;?>">
                        <?php echo $user['UserName']; ?></a>（<?php echo $user['dept_name']; ?>）
                    <div class="mini-report">已汇报</div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<?php endif;?>
