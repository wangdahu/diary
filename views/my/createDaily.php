<div id="daily-dialog-form" title="写日志" style="display: none">
    <form>
        <fieldset>
            <textarea style="width: 494px; height: 200px;" id="daily_content" data-limit="300"></textarea>
            <input type="hidden" name="daily_id" id="daily_id"/>
        </fieldset>
    </form>
</div>

<script>
    $(function() {
        $("#daily-dialog-form").dialog({
            autoOpen: false,
            height: 303,
            width: 532,
            modal: true,
            resizable: false,
            open: function(){
                $("#daily_content").select();
                $('#daily_content').wordLimit();
            },
            buttons: {
                "确定": function(){
                    var content = $("#daily_content").val(),
                    id = $("#daily-dialog-form").find("#daily_id").val();
                    if(!content.length){
                        alert('请填写日志内容');
                        return false;
                    }
                    if($("#daily-dialog-form").find('#word_valid').val()){
                        alert('输入的文字内容大于所规定的字数');
                        return false;
                    }
                    var currentTime = <?php echo $startTime; ?>;
                    $.post('createDaily', {content:content, currentTime:currentTime, id: id}, function(json){
                        location.reload();
                    }), 'json';
                },
                "取消": function() {
                    $(this).dialog("close");
                }
            },
            close: function() {
                $(this).dialog("close");
            }
        });

        $(".write-daily, .no-content-daily").click(function(){
            $("#daily_content").val('');
            $("#daily-dialog-form").find("#daily_id").val('');
            $("#daily-dialog-form").dialog("open");
        });

        $(".js-edit_diary").click(function(){
            var content = $(this).find("script").html();
            $("#daily_content").val($.trim(content));
            $("#daily-dialog-form").find("#daily_id").val($(this).attr('data-daily_id'));
            $("#daily-dialog-form").dialog("open");
        });
    });
</script>
