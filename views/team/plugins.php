<script type="text/javascript" src="../../../misc/vendors/depchoice/edep_plugin.js"></script>
<!--遮罩层-->
<div id="mask" style="display:none"></div>
<!--树的挂靠div-->
<div id="depinfoWin" style="z-index:99999"></div>
<script>
    $(function(){
        var depchoice1 = '';

        $('#js-selected_user').click(function(){
            var object = $(this);
            getDeptWin(object);
            depchoice1.display('show',function(){
                depchoice1.init();
            });
            $('#mask').show();
        });

        function getDeptWin(object) {
            depchoice1 = new depchoice({
                id:'depinfoWin',/*绑定的位置*/
                treeid:'d_tree',/*树绑定的位置*/
                /*数据源（local为本地生成好的json串格式，ajax为需要动态加载数据的方式，需要配合url使用,tree_JSON 构建树的内容,tree_JSON 只有在type为local时使用）*/
                datasource:{"type":"ajax","url":"/admin/dep/getdepinfonew","ajaxdata":[true],"tree_JSON":""},
                treeobj:'_tree',/*构建树的对象名称*/
                mode:'simple',/*有两种模式 简单(simple)和复杂(intricacy) 默认复杂模式(intricacy)*/
                sp:false,
                rs:function(val) {/*获得结果函数*/
                    var jsonVal = $.parseJSON(val),
                    users = jsonVal[1];
                    if(users.length) {
                        var id = users[0]['id'];
                        var type = '<?php echo $type;?>',
                        forward = '<?php echo $forward;?>';
                        location.href  = '/diary/index.php/team/'+type+'?forward='+forward+'&uid='+id;
                    }else {
                        alert('请选择人员');
                    }
                    $('#mask').hide();
                },
                fun:function(val){/*外部函数*/
                    $('#mask').hide();
                }
            });
        }

    });
</script>
