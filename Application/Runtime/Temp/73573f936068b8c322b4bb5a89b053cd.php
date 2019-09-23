<?php
//000000000000s:1431:"<script type='text/javascript'>
function OpenWin(Type) {
   switch (Type) {
        case 'refresh':$.XB.open({ 'type':'refresh','openmode':'2', 'dialog': { 'url': 'refresh/', 'title': '刷新' } });break;
        case 'add':$.XB.open({ 'type':'add','openmode':'0', 'dialog': { 'url': '/admin.php/Operation/Tgadmin/edit/', 'title': '添加' } });break;
        case 'edit':$.XB.open({ 'type':'edit','openmode':'0', 'dialog': { 'url': '/admin.php/Operation/Tgadmin/edit/', 'title': '修改' } });break;
        case 'del':$.XB.open({ 'type':'del','openmode':'3','token': $('input[name=__RequestVerificationToken]').val(), 'dialog': { 'url':'/admin.php/Operation/Tgadmin/del/', 'title': '删除' } });break;
        case 'all': $.XB.open({ 'type': 'all'});break;
        case 'clearall': $.XB.open({ 'type': 'clearall'});break;
        case 'anti': $.XB.open({ 'type': 'anti'});break;}
}
</script>
<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">
<div data-options="name:'refresh'">刷新</div>
<div class="menu-sep"></div>
<div data-options="name:'add'">添加</div>
<div data-options="name:'edit'">修改</div>
<div data-options="name:'del'">删除</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>";
?>