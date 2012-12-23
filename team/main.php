<?php
include dirname(dirname(__FILE__))."/class/DiaryComment.php";
include dirname(dirname(__FILE__))."/class/DiaryViewRecord.php";
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
                    已订阅，<a href="javascript:;" class="js-cancel" data-id="<?php echo $uid;?>" data-username="<?php echo $user['username']; ?>">取消订阅
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
            var username = $(this).attr("data-username"),
            uid = $(this).attr("data-id"),
            type = '<?php echo $setDefault;?>';
            if(confirm("确定要取消对”"+username+"“的订阅？")){
                $.post('cancelSubscribe', {uid: uid, type: type}, function(json){
                    if(json){
                        location.reload();
                    }else{
                        alert('操作失败');
                    }
                }, 'json');
            }
            return false;
        });
    });
</script>
