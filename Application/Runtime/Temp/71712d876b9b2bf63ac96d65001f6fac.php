<?php
//000000000000s:1604:"<script type='text/javascript'>
function OpenWin(Type) {
   switch (Type) {
        case 'refresh':$.XB.open({ 'type':'refresh','openmode':'2', 'dialog': { 'url': 'refresh/', 'title': '刷新' } });break;
        case 'Backup':$.XB.open({ 'type':'Backup','openmode':'2', 'dialog': { 'url': 'Backup/', 'title': '备份' } });break;
        case 'Restore':$.XB.open({ 'type':'Restore','openmode':'2', 'dialog': { 'url': 'Restore/', 'title': '恢复' } });break;
        case 'Download':$.XB.open({ 'type':'Download','openmode':'5', 'dialog': { 'url': '/admin.php/System/Database/Download/', 'title': '下载' } });break;
        case 'del':$.XB.open({ 'type':'del','openmode':'3','token': $('input[name=__RequestVerificationToken]').val(), 'dialog': { 'url':'/admin.php/System/Database/del/', 'title': '删除' } });break;
        case 'all': $.XB.open({ 'type': 'all'});break;
        case 'clearall': $.XB.open({ 'type': 'clearall'});break;
        case 'anti': $.XB.open({ 'type': 'anti'});break;}
}
</script>
<div id="DataListMenu" class="easyui-menu" style="width: 130px;display:none;">
<div data-options="name:'refresh'">刷新</div>
<div class="menu-sep"></div>
<div data-options="name:'Backup'">备份</div>
<div data-options="name:'Restore'">恢复</div>
<div data-options="name:'Download'">下载</div>
<div data-options="name:'del'">删除</div>
<div class="menu-sep"></div>
<div data-options="name:'all'">全选</div>
<div data-options="name:'clearall'">全不选</div>
<div data-options="name:'anti'">反选</div>
<div class="menu-sep"></div>
<div>退出</div>
</div>";
?>