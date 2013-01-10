<script type="text/javascript" src="/misc/js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="/misc/vendors/depchoice/edep_plugin.js"></script>

<div id="opentag">点击打开</div>

<!--遮罩层-->
<div id="mask" style="display:none"></div>
<!--树的挂靠div-->
<div id="depinfoWin" style="z-index:99999"></div>


<script>

var depchoice1 = '';

var _get = {

	val:[]
		
};

$().ready(function(){

	getDeptWin();

	$('#opentag').click(function(){

		depchoice1.display('show',function(){

			/*
				回显数据，只能够用在复杂模式中 mode:'intricacy' ,简单模式不支持回显数据 
				没有数据要回显时:

				depchoice1.inputval = [];
				epchoice1.inputempval = [];
			
			*/
			
			/*部门组装格式 json串 [{"id":"1","name":"总部"},{"id":"2","name":"产品策划"}] */
			depchoice1.inputval = [{"id":"1","name":"总部"},{"id":"2","name":"产品策划"}];
			
			/*人员组装格式 json串 [{"id":"12","name":"11"},{"id":"19","name":"11111"}] */
			depchoice1.inputempval = [{"id":"20","name":"张利让"},{"id":"1","name":"admin"}];
			
			depchoice1.init();
		})

		$('#mask').show();
	});
	
});

function getDeptWin() {

	depchoice1 = new depchoice({

		id:'depinfoWin',/*绑定的位置*/
		treeid:'d_tree',/*树绑定的位置*/
		/*数据源（local为本地生成好的json串格式，ajax为需要动态加载数据的方式，需要配合url使用,tree_JSON 构建树的内容,tree_JSON 只有在type为local时使用）*/
		datasource:{"type":"ajax","url":"/admin/dep/getdepinfonew","ajaxdata":[true],"tree_JSON":""},
		treeobj:'_tree',/*构建树的对象名称*/
		mode:'intricacy',/*有两种模式 简单(simple)和复杂(intricacy) 默认复杂模式(intricacy)*/
		sp:false,
		rs:function(val){/*获得结果函数*/

			$('#mask').hide();
			
			_get.val = val;

			getval();
		},
		fun:function(val){/*外部函数*/
			$('#mask').hide();
		}
	});

}

/*获取返回值
 *说明：
*
*返回值格式如下：
*
* [[{"id":"1","name":"总部"},{"id":"2","name":"产品策划"}],[{"id":"20","name":"张利让"},{"id":"1","name":"admin"}]]
*
* 返回值结果是个多维数组
* 返回结构由两部分组成：
* 左边部分为部门id和name [{"id":"1","name":"总部"},{"id":"2","name":"产品策划"}] 多个值的数组格式
* 右边部分为人员id和name [{"id":"20","name":"张利让"},{"id":"1","name":"admin"}] 多个值的数组格式
* 
 */
function getval(){
	
	debug(_get.val);
	
}

</script>