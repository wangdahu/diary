<?php
$weekarray=array("日","一","二","三","四","五","六");
?>
<style>
.top-page{
    border: 1px solid #888;
    padding: 5px;
}
.selected { background: #ccc}
</style>
<div>
    <button>刷新</button>
    <span class="top-page"><span>上一页 </span><span> 下一页</span></span>
    <strong><?php echo date('y年m月d日')." 周".$weekarray[date("w")];;?></strong>
    <span class="top-page"><a class="selected">日报 </a><a>周报 </a><a>月报</a></span>
</div>
<hr />
