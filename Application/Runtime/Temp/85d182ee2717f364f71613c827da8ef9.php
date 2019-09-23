<?php
//000000000000s:2118:"<script type='text/javascript'>
function OpenWin(Type) {
   switch (Type) {
        case 'refresh':$.XB.open({ 'type':'refresh','openmode':'2', 'dialog': { 'url': 'refresh/', 'title': '刷新' } });break;
        case 'detail':$.XB.open({ 'type':'detail','openmode':'1', 'dialog': { 'url': '/admin.php/Members/MemInfo/detail/', 'title': '详情' } });break;
        case 'edit':$.XB.open({ 'type':'edit','openmode':'0', 'dialog': { 'url': '/admin.php/Members/MemInfo/edit/', 'title': '修改' } });break;
        case 'del':$.XB.open({ 'type':'del','openmode':'3','token': $('input[name=__RequestVerificationToken]').val(), 'dialog': { 'url':'/admin.php/Members/MemInfo/del/', 'title': '删除' } });break;
        case 'send':$.XB.open({ 'type':'send','openmode':'0', 'dialog': { 'url': '/admin.php/Members/MemInfo/send/', 'title': '发送', 'save': {'url': 'Members/MemInfo/sendwxmsg' } } });break;
        case 'SendMes':$.XB.open({ 'type':'SendMes','openmode':'0', 'dialog': { 'url': '/admin.php/Members/Sendmessage/index/', 'title': '群发消息', 'save': {'url': 'Members/Sendmessage/Save' } } });break;
        case 'exportexcel':$.XB.open({ 'type':'exportexcel','openmode':'6', 'dialog': { 'url': 'exportexcel/', 'title': '导出Excel' } });break;
        case 'all': $.XB.open({ 'type': 'all'});break;
        case 'clearall': $.XB.open({ 'type': 'clearall'});break;
        case 'anti': $.XB.open({ 'type': 'anti'});break;}
}
</script>
<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">
<div data-options="name:'refresh'">刷新</div>
<div data-options="name:'detail'">详情</div>
<div data-options="name:'edit'">修改</div>
<div data-options="name:'del'">删除</div>
<div data-options="name:'send'">发送</div>
<div data-options="name:'SendMes'">群发消息</div>
<div data-options="name:'exportexcel'">导出Excel</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>";
?>