<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>小白猫</title>
    <meta name="viewport" content="width=device-width" />
    <meta name="renderer" content="webkit|ie-comp|ie-stand" />
    <link href="/Public/Admin/images/H/login.css" rel="stylesheet" />
    <link href="/Public/Admin/JS/EasyUI/easyui.css" rel="stylesheet" />
    <link href="/Public/Admin/images/H/Main.css" rel="stylesheet" />
</head>
<body style="background: #0c82c6 url(/Public/Admin/images/H/light.png) no-repeat center top; overflow: hidden;padding-top:50px;">
<form id="FF" method="post" action="<?php echo U('System/Admin/dologin');?>">
    <div id="mainBody">
        <div id="cloud1" class="cloud"></div>
        <div id="cloud2" class="cloud"></div>
    </div>
    <div class="logintop">
        <span>欢迎您来到登录界面</span>
        <ul>
            <li><a href="javascript:void(0);">&copy;2017 版权所有</a></li>
        </ul>
    </div>
    <div class="loginbody">
        <div class="logo">
            <img src="/Public/Admin/images/H/logo.png">
        </div>
        <div class="content">
            <div class="Loginform">
                <div class="form-message">
                    <div id="LMes" class="LMes"></div>
                </div>
                <div class="form-account">
                    账户
                    <input id="UserName" name="username" type="text">
                </div>
                <div class="form-password">
                    密码
                    <input id="UserPsd" name="password" type="password">
                </div>
                <div class="form-bottom">
                    <div class="p5">
                        <input name="Code" id="Code" maxlength="4" type="text" style="height:38px;">
                    </div>
                    <div class="p6">
                        <img id="imgValidateCode" onclick="this.src=this.src+'?'+Math.random()" src="<?php echo U('System/Login/selfverify');?>" width="100" height="40">
                    </div>
                    <div class="LoginBt" onclick="Login()">登录</div>
                    <input type="hidden" id="MacAddress" name="MacAddress" value=""/>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="loginbm">皖ICP备11053300号 适用浏览器：IE8+、360、FireFox、Chrome、Safari、搜狗、腾讯TT、QQ等浏览器。 最小分辨率：1024*768</div>

<script src="/Public/Admin/JS/jquery.min.js"></script>
<!--<script src="/Public/Admin/JS/cloud.js"></script>-->
<script src="/Public/Admin/JS/EasyUI/jquery.easyui.min.js"></script>
<?php if($mac_status == 1): ?><script src="/Public/Admin/JS/Lodop/LodopFuncs.js"></script><?php endif; ?>
<script src="/Public/Admin/JS/XB.js"></script>
<script language="javascript">

    $(function () {

        if (top.location != self.location) {
            top.location = self.location;
        }
        $("#UserName").focus();
        $(document).on('keyup', function (event) {
            if (event.keyCode == 13) {
                Login();
            }
        });
        //判断后台是否开启获取MAC地址 【获取缓存点】
        if (1==1)
        {
            GetSystemInfo('NetworkAdapter.1.PhysicalAddress');
        }
    })
    function GetSystemInfo(strINFOType) {

        try {
            LODOP = getLodop();

            $("#MacAddress").val(LODOP.GET_SYSTEM_INFO(strINFOType));
        } catch (e) {

        }
    }
    function Login() {
        var UserName = $("#UserName"), UserPsd = $("#UserPsd"), Code = $("#Code"), LMes = $("#LMes");
        if (UserName.val().length == 0) {
            LMes.html('<div class="LMes3">很抱歉，请先输入登录账户名！</div>');
        }
        else if (UserPsd.val().length == 0) {
            LMes.html('<div class="LMes3">很抱歉，请先输入登录密码！</div>');
        }
        else if (Code.val().length == 0) {
            LMes.html('<div class="LMes3">很抱歉，请先输入验证码！</div>');
        }
        else {

            var parm = { "UserName": escape(UserName.val()), "UserPsd": escape(UserPsd.val()), "Code": escape(Code.val()), "MacAddress": escape($("#MacAddress").val()) };
            LMes.html('<div class="LMes2">正在验证用户名和密码，请稍等...</div>');
            $.post("<?php echo U('System/Login/doLogin');?>", parm, function (data, textStatus) {
                if (data.result) {
                    window.location.href = data.des;
                } else {
                    LMes.html('<div class="LMes3">' + data.message + '</div>');
                    $('#imgValidateCode').attr('src',"<?php echo U('System/Login/selfverify');?>?"+ Math.random());
                    $('input#Code').val('');
                }
            }, "json");
        }
    }
</script>
</body>
</html>