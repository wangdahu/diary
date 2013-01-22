<?php
$wiseucUrl = "wisetong://message/?uid=".$userInfo['LoginName']."&myid=".$diary->LoginName;

?>
<!--功能操作开始-->
<div class="todo clearfix">
    <a href="javascript:" onclick="history.back(-1);" class="a_01 fl">返回</a>
     <p class="fl mg10" ><strong><span style="font-size: 16px">【<?php echo $tagName?>】</span>  <a href="<?php echo $wiseucUrl;?>"><?php echo $userInfo['UserName'];?></a><?php echo "（".$userInfo['dept_name']."-".$userInfo['Title']."）";?> 共有 <?php echo $num;?> 项工作</strong></p>
</div>
<!--功能操作结束-->
