<?php
$title = "标签设置";
$setDefault = 'tag';

include dirname(dirname(__FILE__))."/class/DiaryDaily.php";
// 获取颜色列表
$colorList = DiaryDaily::getColorList($diary);
$tagList = DiaryDaily::getTagList($diary);

$defaultColorId = rand(1,20);
?>
<?php include "views/layouts/header.php"; ?>
<?php include "views/set/top.php"; ?>
<style>
    .tag {border: 1px solid #ccc; width: 850px; padding: 0 auto 15px auto; text-align:left;}
    .tag thead tr {background-color: #ccc; height: 30px; font-weight: bold;}
    .tag tbody tr {height: 28px;}
    .tag td {padding-left: 10px;}
    .tag .td-right {text-align: right; padding-right: 20px;}
    form .default-color {margin: 9px 5px; float: left; width: 12px; height: 12px;}
    .color-list { width: 12px; height: 12px; background-color: #ccc; padding: 1px; margin: -1px;}
    form .show-color-list {margin: 9px 2px; border: 1px solid #ccc; width: 230px; display: none;}
    form .show-color-list td{padding: 5px;}
    form .show-color-list .color-selected {border: 1px solid #ccc; padding: 2px; margin: -2px;}
</style>
<div class="content">
    <div class="set_bar mb25" style="margin-left: -10px;">
        <!--标签设置开始-->
        <h2 class="pt25"><a href="javascript:;" class="tjbq js-add-tag" title="添加标签"></a></h2>

        <div class="c_t mt10"></div>
        <div class="c_c">
            <table class="tag">
                <thead>
                    <tr>
                        <td>标签名称</td>
                        <td>工作数</td>
                        <td class="td-right">操作</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tagList as $tag):?>
                    <tr>
                        <td><div class="color-list" style="float: left; margin-right: 5px; background-color: <?php echo $colorList[$tag['color_id']]?>"></div><?php echo $tag['tag'];?></td>
                        <td><?php echo $tag['count'];?></td>
                        <td class="td-right"><a href="javascript:;">编辑</a> <a href="javascript:;">删除</a></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <!--标签设置结束-->
        <div class="c_b"></div>
    </div>
</div>
<?php include "views/layouts/footer.php"; ?>
<div id="dialog-form" title="添加标签">
    <form>
        <fieldset>
            <input id="tag" name="tag" size="35" maxlength="40" placeholder="输入标签名称" style="line-height: 24px;"/>
            <div style="margin: 10px -3px ; ">
                <div class="select-color" style="line-height: 30px;">
                    <div class="default-color" style="background-color: <?php echo $colorList[$defaultColorId];?>;"></div><span>选择颜色</span>
                </div>
                <input name="color_id" id="color_id" type="hidden" value="<?php echo $defaultColorId;?>" />
                <div class="show-color-list">
                    <table>
                        <?php for($row = 0; $row < 2; $row++):?>
                        <tr>
                            <?php for($col = 1; $col < 11; $col++):?>
                            <td>
                                <div class="<?php echo $defaultColorId == ($row*10 + $col) ? 'color-selected' : ''?>">
                                    <div class="color-list" data-id="<?php echo $row*10 + $col;?>" style="background-color: <?php echo $colorList[$row*10 + $col]?>;"></div>
                                </div>
                            </td>
                            <?php endfor;?>
                        </tr>
                        <?php endfor;?>
                    </table>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<script>
    $(function(){
        $("#dialog-form").dialog({
            autoOpen: false,
            height: 210,
            width: 330,
            modal: true,
            buttons: {
                "确定": function(){
                    var tag = $("#tag").val(),
                    color_id = $("#color_id").val();
                    if(!tag.length){
                        alert('请填写标签内容');
                        return false;
                    }
                    $.post('createTag', {tag:tag, color_id:color_id}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                allFields.val("").removeClass("ui-state-error");
            }
        });
        $(".js-add-tag").button().click(function(){$("#dialog-form").dialog("open");});

        $(".select-color").click(function(){
            $(this).hide();
            $(".show-color-list").show();
        });

        $(".color-list").click(function(){
            $(".show-color-list").hide();
            $(".default-color").css('background-color', $(this).css("background-color"));
            $("#color_id").val($(this).attr("data-id"));
            $(".select-color").show();
        });
    });
</script>
