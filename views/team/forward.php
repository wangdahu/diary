<?php
$user = DiaryUser::getInfo($uid);
?>
<div class="content">
    <!--本月总结开始-->
    <div class="content_bar mb25">
        <a href="<?php echo $backUrl; ?>" class="fl btn_back mr10"></a>
        <h2 class="content_tit clearfix user-info">
            <a href="<?php echo $wiseucUrl;?>"><?php echo $user['UserName'];?></a><?php echo "（".$user['dept_name']."-".$user['Title']."）";?>
        </h2>
        <h2 class="content_tit clearfix">
            <p><?php echo $forwardTitle; ?></p>
        </h2>
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong>还未到汇报时间</strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
    </div>
</div>
