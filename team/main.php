<?php
include dirname(dirname(__FILE__))."/class/DiaryComment.php";
include dirname(dirname(__FILE__))."/class/DiaryViewRecord.php";
$showObject = $forward < 0 ? false : true;
?>
<?php if($showObject):?>
<div class="content">
    <div class="set_bar mb25">
        <!--标签设置开始-->
        <ul class="dy">
            <?php foreach($teamShowObject as $uid): ?>
            <?php
                 $user = User::getInfo($uid);
                 $url = "daily?forward=$forward&uid=$uid";
                 if($setDefault === 'week'){
                     $url = "weekly?forward=$forward&uid=$uid";
                 }elseif($setDefault === 'month'){
                     $url = "monthly?forward=$forward&uid=$uid";
                 }
                 $status = DiaryViewRecord::checkUser($diary, $type, $uid, $object);
            ?>
            <li style="<?php echo $status ? '' : 'border: 1px solid #000;'?>">
                <a href="<?php echo $url;?>">
                    <div class="pic">
                        <img src="<?php echo $user['photo']; ?>" />
                </div>
                </a>
                <p>
                    <a href="<?php echo $url;?>">
                        <?php echo $user['username']; ?></a>（<?php echo $user['dept_name']; ?>）<br />
                    已汇报
                    </a>
                    <span style="color:red;"><?php echo DiaryComment::checkUserObjectComment($diary, $uid, $type, $object) ? '评论' : '';?></span>
                </p>
            </li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<?php endif;?>
