<?php
//000000000000s:2131:"<script type='text/javascript'>
function OpenWin(Type) {
   switch (Type) {
        case 'refresh':$.XB.open({ 'type':'refresh','openmode':'2', 'dialog': { 'url': 'refresh/', 'title': '刷新' } });break;
        case 'add':$.XB.open({ 'type':'add','openmode':'0', 'dialog': { 'url': '/admin.php/System/Administrator/edit/', 'title': '添加' } });break;
        case 'edit':$.XB.open({ 'type':'edit','openmode':'0', 'dialog': { 'url': '/admin.php/System/Administrator/edit/', 'title': '修改' } });break;
        case 'del':$.XB.open({ 'type':'del','openmode':'3','token': $('input[name=__RequestVerificationToken]').val(), 'dialog': { 'url':'/admin.php/System/Administrator/del/', 'title': '删除' } });break;
        case 'BindIP':$.XB.open({ 'type':'BindIP','openmode':'2', 'dialog': { 'url': 'BindIP/', 'title': '绑定IP' } });break;
        case 'BindMAC':$.XB.open({ 'type':'BindMAC','openmode':'2', 'dialog': { 'url': 'BindMAC/', 'title': '绑定MAC' } });break;
        case 'bindChannel':$.XB.open({ 'type':'bindChannel','openmode':'0', 'dialog': { 'url': '/admin.php/System/Administrator/bindChannel/', 'title': '绑定渠道', 'save': {'url': 'System/Administrator/bindChannelsave' } } });break;
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
<div data-options="name:'BindIP'">绑定IP</div>
<div data-options="name:'BindMAC'">绑定MAC</div>
<div data-options="name:'bindChannel'">绑定渠道</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>";
?>