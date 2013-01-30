<div id="tag-dialog-form" style="display:none;" title="添加标签">
    <form method="post">
        <fieldset>
            <input id="tag" name="tag" maxlength="40" placeholder="输入标签名称" style="line-height: 24px; width: 240px;"/>
            <div style="margin: 10px -3px ; ">
                <div class="select-color" style="line-height: 30px;">
                    <div class="default-color" style="background-color: <?php echo $colorList[$defaultColorId];?>;"></div><span>选择颜色</span>
                </div>
                <input name="color_id" id="color_id" type="hidden" value="<?php echo $defaultColorId;?>" />
                <input name="daily_id" id="daily_id" type="hidden" value="" />
                <input name="id" id="id" type="hidden" value="" />
                <ul class="show-color-list clearfix">
                    <?php for($row = 1; $row < 21; $row++):?>
                          <li class="color-list-wrapper" data-id="<?php echo $row;?>"><div style="background-color: <?php echo $colorList[$row]?>;"></div></li>
                    <?php endfor;?>
                </ul>
            </div>
        </fieldset>
    </form>
</div>

<script>
    $(function(){
        $("#tag-dialog-form").dialog({
            autoOpen: false,
            height: 200,
            width: 270,
            modal: true,
            open: function(){
                $("#tag-dialog-form").keypress(function(e) {
                    if (e.keyCode == $.ui.keyCode.ENTER) {
                        $(this).parent().find("button:eq(0)").trigger("click");
                    }
                });
            },
            buttons: {
                "确定": function(){
                    var tag = $("#tag").val(),
                    color_id = $("#color_id").val(),
                    id = $("#id").val();
                    var daily_id = $('#daily_id').val();
                    if(!tag.length){
                        alert('请填写标签内容');
                        return false;
                    }
                    $.post('/diary/index.php/set/operateTag', {tag:tag, color_id:color_id, id: id, diary_id: daily_id}, function(json){
                        if(json != 0){
                            location.reload();
                        }else{
                            alert('标签重复！');
                            $("#tag").select();
                            return false;
                        }
                    });
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $(".default-color").css('background-color', $(this).css("background-color"));
                $("#color_id").val($(this).attr("data-id"));
                $(this).dialog("close");
            }
        });

        // 添加
        $(".js-add-tag").click(function(){
            var len = $(this).closest('.js-all-tag').find(':checked').length;
            if(len >= 5){
                $(this).attr('checked', false);
                alert('单个日志不能超过五个标签哦');
                return false;
            }
            if($(this).attr('data-daily_id')){
                $('#daily_id').val($(this).attr('data-daily_id'));
            }
            $("#tag-dialog-form").dialog("open");
        });

        // 编辑
        $(".js-edit-tag").click(function(){
            $("#tag-dialog-form").attr('title', '编辑标签');
            $("#color_id").val($(this).attr('data-color_id'));
            $("#tag").val($(this).attr('data-tag'));
            $("#id").val($(this).attr('data-id'));
            $(".default-color").css('background-color', $(this).attr("data-color"));
            $("#tag-dialog-form").dialog("open");
        });

        // 删除
        $(".js-delete-tag").click(function(){
            var id = $(this).attr('data-id'),
            tag = $(this).attr('data-tag');
            if(confirm("确定要删除标签“"+tag+"”？")){
                $.post('operateTag', {tag:tag, id:id, action:'delete'}, function(json){
                    if(json != 0){
                        location.reload();
                    }else{
                        alert('删除失败！');
                    }
                });
            }
            return false;
        });

        $(".select-color").click(function(){
            $(this).hide();
            var colorId = $('#color_id').val();
            $(".show-color-list").show()
                .find('[data-id=' + colorId + ']').addClass('color-selected').siblings().removeClass('color-selected');
        });

        $(".color-list-wrapper").click(function(){
            $(".show-color-list").hide();
            $(".default-color").css('background-color', $(this).children().css("background-color"));
            $("#color_id").val($(this).attr("data-id"));
            $(".select-color").show();
        });
    });
</script>
