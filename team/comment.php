<?php
include dirname(dirname(__FILE__))."/class/DiaryComment.php";
$commentList = DiaryComment::getObjectComment($diary, $uid, $type, $object);
?>
<!--评论开始-->
<div class="content_bar">
    <h2 class="content_tit clearfix mb10">
        <p class="p_icon">评论</p>
        <span class="fr">评论（<?php echo count($commentList); ?>）</span>
        <a href="#" class="fr">汇报：12人 已阅：6人</a>
    </h2>
    <?php foreach($commentList as $comment): ?>
    <?php $user = User::getInfo($comment['uid']);?>
    <div class="comment_box">
        <div class="c_t"></div>
        <div class="c_c clearfix">
            <div class="pic"><img src="<?php echo $user['photo']; ?>" alt="" /></div>
            <div class="comment_t">
                <h2>
                    <a href="javascript:;"><?php echo $user['username']; ?>（<?php echo $user['dept_name']; ?>）</a>
                    <span>
                        <?php echo date('Y-m-d H:i', $comment['add_time']); ?>
                    </span>
                </h2>
                <p><?php echo nl2br($comment['content']); ?></p>
            </div>
        </div>
        <div class="c_b"></div>
    </div>
    <?php endforeach;?>
</div>
<!--评论结束-->
<!--发表评论开始-->
<form method="post" id="comment-form">
    <div class="content_bar">
        <div class="c_t"></div>
        <div class="c_c">
            <div class="comment_f">
                <button class="fr" type="submit">评论</button>
            </div>
            <div class="ftextarea">
                <textarea name="content" id="content" class="textarea_comment" maxlength="1000"></textarea>
            </div>
            <input type="hidden" value="<?php echo $type;?>" name="type" id="type"/>
            <input type="hidden" value="<?php echo $uid;?>" name="to_uid" id="to_uid"/>
            <input type="hidden" value="<?php echo $object;?>" name="object" id="object"/>
        </div>
        <div class="c_b"></div>
    </div>
</form>
<script>
    $(function(){
        $("#comment-form").submit(function(){
            var form = $(this), content = $('#content').val();
            if(!content.length){
                alert('请填写内容');
                $('#content').select();
                return false;
            }
            $.post('createComment', form.serialize(), function(json){
                if(json){
                    location.reload();
                }else{
                    alert("评论失败，请重试");
                }
            }), 'json';
            return false;
        });
    });
</script>
<!--发表评论结束-->
