<div id="daily-dialog-form" title="写日志">
    <form>
        <fieldset>
            <textarea cols="60" rows="12" id="daily_content"></textarea>
            <input type="hidden" name="daily_id" id="daily_id"/>
        </fieldset>
    </form>
</div>

<script>
    $(function() {
        $("#daily-dialog-form").dialog({
            autoOpen: false,
            height: 300,
            width: 530,
            modal: true,
            open: function(){
                $("#daily_content").select();
            },
            buttons: {
                "写日志": function(){
                    var content = $("#daily_content").val(),
                    id = $("#daily-dialog-form").find("#daily_id").val();
                    if(!content.length){
                        alert('请填写日志内容');
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

        $(".write-daily").button().click(function(){
            $("#daily_content").html('');
            $("#daily_id").val(0);
            $("#daily-dialog-form").dialog("open");
        });

        $(".js-edit_diary").click(function(){
            var content = $(this).find("div").html();
            $("#daily_content").html(content);
            $("#daily-dialog-form").find("#daily_id").val($(this).attr('data-daily_id'));
            $("#daily-dialog-form").dialog("open");
        });
    });
</script>
