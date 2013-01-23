<script type="text/javascript" src="../../../misc/vendors/depchoice/edep_plugin.js"></script>
<!--遮罩层-->
<div id="mask" style="display:none"></div>
<!--树的挂靠div-->
<div id="depinfoWin" style="z-index:99999"></div>
<script>
    $(function(){
        var depchoice1 = '';
        var _get = {
            val:[]
        };

        $('.opentag').click(function(){
            var object = $(this);
            getDeptWin(object);
            depchoice1.display('show',function(){
                /*
                  回显数据，只能够用在复杂模式中 mode:'intricacy' ,简单模式不支持回显数据
                  没有数据要回显时:
                  depchoice1.inputval = [];
                  dpchoice1.inputempval = [];
                */
                /*部门组装格式 json串 [{"id":"1","name":"总部"},{"id":"2","name":"产品策划"}] */
                var oldUsers = object.parent().next().next().val(),
                oldDepts = object.parent().next().next().next().val();
                depchoice1.inputval = [];
                depchoice1.inputempval = [];
                if(oldUsers){
                    var userArr = oldUsers.split(',');
                    var userObject = $.map(userArr, function(id) {
                        return {id: id, name: '' };
                    });
                    depchoice1.inputempval = userObject;
                }
                /*人员组装格式 json串 [{"id":"12","name":"11"},{"id":"19","name":"11111"}] */
                if(oldDepts){
                    var deptArr = oldDepts.split(','),
                    deptObject = $.map(deptArr, function(id) {
                        return {id: id, name: '' };
                    });
                    depchoice1.inputval = deptObject;
                }
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
                mode:'intricacy',/*有两种模式 简单(simple)和复杂(intricacy) 默认复杂模式(intricacy)*/
                sp:false,
                rs:function(val) {/*获得结果函数*/
                    $('#mask').hide();
                    var jsonVal = $.parseJSON(val),
                    depts = jsonVal[0],
                    users = jsonVal[1];
                    var user_ids = [],
                    user_names = [],
                    dept_ids = [],
                    dept_names = [];
                    for(var i=0; i<users.length; i++){
                        user_ids.push(users[i]['id']);
                        user_names.push(users[i]['name']);
                    };
                    for(var i=0; i<depts.length; i++){
                        dept_ids.push(depts[i]['id']);
                        dept_names.push("["+depts[i]['name'] + "]");
                    };
                    var text = user_names.concat( dept_names).join('，');
                    object.parent().next().children().text(text);
                    object.parent().next().next().val(user_ids.join(','))
                        .next().val(dept_ids.join(','));
                },
                fun:function(val){/*外部函数*/
                    $('#mask').hide();
                }
            });
        }
    });
</script>
