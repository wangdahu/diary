<?php
include dirname(dirname(__FILE__))."/class/DiaryComment.php";
?>
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
            ?>
            <li>
                <a href="<?php echo $url;?>">
                    <div class="pic">
                        <img src="<?php echo $user['photo']; ?>" alt="" />
                </div>
                </a>
                <p>
                    <a href="<?php echo $url;?>">
                        <?php echo $user['username']; ?></a>（<?php echo $user['dept_name']; ?>）<br />
                    已订阅，<a href="javascript:;" class="js-cancel" data-uid="<?php echo $uid;?>">取消订阅
                    </a>
                    <span style="color:red;"><?php echo DiaryComment::checkUserObjectComment($diary, $uid, $type, $object) ? '评论' : '';?></span>
                </p>
            </li>
            <?php endforeach;?>
        </ul>
        <!--标签设置结束-->
    </div>
</div>
<script>
    $(function(){
        $(".js-cancel").click(function(){
            console.log($(this).attr('data-id'));
        });
    });
</script>
