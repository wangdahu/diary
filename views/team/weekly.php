<div class="content">
    <!--今日工作开始-->
    <div class="content_bar mb25">
        <h2 class="content_tit clearfix">
            <p>周报</p>
            <?php if($allowPay):?>
            <?php if($num):?>
            <a href="javascript:" class="fr mr10 pay-diary js-pay_daily"></a>
            <?php else:?>
            <a class="fr mr10 pay-disabled"></a>
            <?php endif;?>
            <?php endif;?>
            <?php if(!$isReported):?>
            <a href="javascript:" class="write-<?php echo $type?> fr mr10"></a>
            <?php endif;?>
        </h2>

        <?php if(!$weeklys):?>
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
        <div class="c_t mt10"></div>
        <div class="c_c">
            <div class="c_c_c">
                <div>
                    <p style="font-size: 16px;color: red; text-align: center; line-height: 100px;">
                        <strong><?php echo $weeklys['content']; ?></strong>
                    </p>
                </div>
            </div>
        </div>
        <div class="c_b"></div>
        <?php endif;?>
        <?php if($weekDate): foreach($weekDate as $k => $v):?>
        <div>
            <div class="c_t mt10"></div>
            <div class="c_c">
                <div>
                    <p style="line-height:30px;">
                        <strong><?php echo "周".$k." ".$v; ?></strong>
                        <span>工作：<?php echo count($dailys[$v]);?>项</span>
                    </p>
                </div>

                <?php if($dailys[$v]): foreach($dailys[$v] as $date => $daily):?>
                <?php
                     $tagList = array();
                     $tagList = DiaryDaily::getDailyTag($diary, $daily['id']);
                     $tagIds = array_keys($tagList);
                ?>
                <div class="c_c_c "  style="padding: 10px 0;border-top: 1px solid #DADADA">
                    <div>
                        <p><?php echo nl2br($daily['content']);?></p>
                    </div>
                    <br>
                    <div style="float: right; margin-top: -20px;">
                        <span>
                            <?php foreach($tagList as $tag):?>
                            <div style="float: left; margin: 0 4px; background-color: <?php echo $tag['color']?>;">
                                <div title="<?php echo $tag['tag']?>" class="ellipsis" style="max-width: 120px; float: left; ">
                                    <span style="margin:4px;"><?php echo $tag['tag']?></span>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </span>
                         <span style="padding-left: 25px;"><?php echo date('y-m-d H:i', $daily['fill_time']);?></span>
                    </div>
                </div>
                <?php endforeach; endif;?>
            </div>
            <div class="c_b"></div>
        </div>

        <?php endforeach; endif;?>
    </div>
    <!--今日工作结束-->
    <?php if($showCommit):?>
     <?php include dirname(dirname(dirname(__FILE__)))."/team/comment.php"; ?>
    <?php endif;?>
</div>
