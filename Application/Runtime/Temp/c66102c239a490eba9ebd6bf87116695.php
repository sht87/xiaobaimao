<?php
//000000000000s:1164:"<script type='text/javascript'>
function OpenWin(Type) {
   switch (Type) {
        case 'refresh':$.XB.open({ 'type':'refresh','openmode':'2', 'dialog': { 'url': 'refresh/', 'title': '刷新' } });break;
        case 'edit':$.XB.open({ 'type':'edit','openmode':'0', 'dialog': { 'url': '/admin.php/Statistics/product/edit/', 'title': '修改' } });break;
        case 'exportexcel':$.XB.open({ 'type':'exportexcel','openmode':'6', 'dialog': { 'url': 'exportexcel/', 'title': '导出Excel' } });break;
        case 'all': $.XB.open({ 'type': 'all'});break;
        case 'clearall': $.XB.open({ 'type': 'clearall'});break;
        case 'anti': $.XB.open({ 'type': 'anti'});break;}
}
</script>
<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">
<div data-options="name:'refresh'">刷新</div>
<div data-options="name:'edit'">修改</div>
<div data-options="name:'exportexcel'">导出Excel</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>";
?>