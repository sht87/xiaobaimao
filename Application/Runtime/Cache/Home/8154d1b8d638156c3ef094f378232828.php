<?php if (!defined('THINK_PATH')) exit();?>		<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link type="text/css" href="/Public/css/jquery-weui.min.css" rel="stylesheet">
    <link type="text/css" href="/Public/css/weui.min.css"  rel="stylesheet"/>
    <link rel="stylesheet" href="/Public/css/app.css">
    <title><?php echo $SEOTitle?$SEOTitle:$GLOBALS['BasicInfo']['SEOTitle'];?></title>
</head>
<body>

		<style type="text/css">
			.weui-input{
				color: #434343;
			}
		</style>
		<header class="header"  >
			<a href="<?php echo U('index');?>" class="go_left"></a>
			 <span class="header_til"><?php echo ($title); ?></span>
		</header>
      <!---->
      <div class="weui-cells weui-cells_form" style="margin-top: 50px;">
		  <form action="#" id="formf" method="post">
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">头像</label></div>
                <div class="weui-cell__bd">
                  <a href="javascript:void(0);" style="display: block;width:32px;height: 32px;" class="fr">
                      <!--<img src="/Public/images/photo.png" style="width:32px; ">-->
                      <?php if($memInfo['HeadImg'] == NULL): ?><img src="/Public/images/photo.png" id="photo" style=" width:32px; display:block;   position:absolute; top:10px; right:5% ">
                          <?php else: ?>
                          <img src="<?php echo ($memInfo["HeadImg"]); ?>" id="photo" style=" width:32px; display:block;   position:absolute; top:10px; right:5%"><?php endif; ?>
                      <input type="hidden" name="HeadImg" id="HeadImg" value="<?php echo ($memInfo["HeadImg"]); ?>">
                      <div class="per_float" id="upload" style=" width:32px; display:block;   position:absolute; top:5px; right:5%;height:32px "></div>
                  </a>
                  <div class="clear"></div>
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">  真实姓名</label></div>
                <div class="weui-cell__bd">
                  <input class="weui-input" id="TrueName" name="TrueName" value="<?php echo ($memInfo["TrueName"]); ?>" placeholder="请填写真实姓名" style="text-align: right;" />
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">身份证号</label></div>
                <div class="weui-cell__bd">
                  <input class="weui-input" id="CardNo" name="CardNo" value="<?php echo ($memInfo["CardNo"]); ?>" placeholder="请填写身份证号码" style="text-align: right;" onkeyup="this.value=this.value.replace(/[^\w\.\/]/ig,'')" maxlength="18"/>
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">手机号</label></div>
                <div class="weui-cell__bd">
                    <div class="weui-input" style="text-align: right;"><?php echo ($memInfo["Mobile"]); ?></div>
                </div>
              </div>
              <!-- <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">推荐码</label></div>
                <div class="weui-cell__bd">
                  <div class="weui-input" style="text-align: right;"><?php echo ($memInfo["Tjcode"]); ?></div>
                </div>
              </div>
              <?php if($memInfo["Referee"] != 0): ?><div class="weui-cell">
                      <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">推荐人</label></div>
                      <div class="weui-cell__bd">
                          <div class="weui-input"  style="text-align: right;">
                              <?php echo ($memInfo["ReferName"]); ?>
                          </div>
                      </div>
                  </div><?php endif; ?> -->
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">身份</label></div>
                <div class="weui-cell__bd">
                    <select name="Position" id="Position" class="weui-input" dir="rtl" style="border:1px solid transparent">
                        <option value="0">请选择</option>
                        <option value="1" <?php if($memInfo["Position"] == 1): ?>selected<?php endif; ?>>学生党</option>
                        <option value="2" <?php if($memInfo["Position"] == 2): ?>selected<?php endif; ?>>上班族</option>
                        <option value="3" <?php if($memInfo["Position"] == 3): ?>selected<?php endif; ?>>自主创业</option>
                        <option value="4" <?php if($memInfo["Position"] == 4): ?>selected<?php endif; ?>>无业</option>
                    </select>
                  <!--<input class="weui-input" placeholder="上班族" style="text-align: right;">-->
                </div>
              </div>
              <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">有无信用卡</label></div>
                <div class="weui-cell__bd">
                    <select name="IsCredit" id="IsCredit" class="weui-input" dir="rtl" style="border:1px solid transparent">
                        <option value="0" <?php if($memInfo["IsCredit"] == 0): ?>selected<?php endif; ?>>无</option>
                        <option value="1" <?php if($memInfo["IsCredit"] == 1): ?>selected<?php endif; ?>>有</option>
                    </select>
                </div>
              </div>
               <div class="weui-cell">
                <div class="weui-cell__hd"><label class="weui-label" style="color:#434343;">手机使用时长</label></div>
                <div class="weui-cell__bd">
                    <select name="UseTime" id="UseTime" class="weui-input" dir="rtl" style="border:1px solid transparent">
                        <option value="1" <?php if($memInfo["UseTime"] == 1): ?>selected<?php endif; ?>>一年以下</option>
                        <option value="2" <?php if($memInfo["UseTime"] == 2): ?>selected<?php endif; ?>>一年以上</option>
                        <option value="3" <?php if($memInfo["UseTime"] == 3): ?>selected<?php endif; ?>>两年以上</option>
                        <option value="4" <?php if($memInfo["UseTime"] == 4): ?>selected<?php endif; ?>>三年以上</option>
                    </select>
                </div>
              </div>
		  </form>
	  </div>

        <div style="padding: 30px 0;">
            <a href="javascript:void(0);" class="btn_want" onclick="saveinfo()" style="width: 90%;">保存</a>
        </div>
	    <!---->
      <!--页面悬浮-->
      <div class="">
        <?php if($GLOBALS['BasicInfo']['Ytstatus']=='3'):?>
        <a href="<?php echo ($GLOBALS['BasicInfo']['YtanUrl']); ?>"  class="suspend_icon1"><img src="<?php echo ($GLOBALS['BasicInfo']['YtanImg']); ?>"></a>
        <?php endif;?>
        <a href="<?php echo U('Index/index');?>" class="suspend_icon2"><img src="/Public/images/suspend_icon2.png"></a>
        <a href="javascript:history.go(-1)" class="suspend_icon3"><img src="/Public/images/suspend_icon3.png"></a>
      </div>
      <!--页面悬浮 end-->
        <script src="/Public/js/jquery-2.1.4.js"></script>
        <script src="/Public/js/jquery-weui.min.js"></script>
        <script src="/Public/js/common.js"></script>
        <script type="text/javascript" src="/Public/artDialog/artDialog.min.js"></script>
        <link rel="stylesheet" href="/Public/artDialog/skins/aero.css">
        <script type="text/javascript" src="/Public/artDialog/iframeTools.js"></script>
        <script src="/Public/js/mui.min.js"></script>
        </body>
        </html>

		<script src="/Public/js/jquery.Huploadify.js" type="text/javascript"></script>
		<script type="text/javascript">
            setTimeout
			$('#upload').Huploadify({
				auto:true,
				fileTypeExts:"<?php echo ($exts); ?>",
				multi:true,
				showUploadedPercent:true,
				showUploadedSize:true,
				fileSizeLimit:"<?php echo ($GLOBALS['BasicInfo']['PicSize']); ?>",
				buttonText:'&nbsp;&nbsp;&nbsp;&nbsp;',
				uploader:'/Member/upload?Path=Large',
				removeTimeout:0,
				itemTemplate:'',
				onUploadStart:function(){},
				onInit:function(){},
				onUploadComplete:function(file, data){
					var msg = eval('(' + data + ')');
					if (msg.result == 0) {
            $.alert(msg.message);
					} else {
						$('#photo').attr('src', msg.path);
						$('#HeadImg').val(msg.path);
					}
				},
				onDelete:function(file){
				}
			});

			function saveinfo() {
                var TrueName=$.trim($('#TrueName').val());
			          var CardNo=$.trim($('#CardNo').val());
                if(!TrueName){
                    $.alert("请填写您的真实姓名");
                    return false;
                }
                if(!CardNo){
                    $.alert("请填写您的身份证号码");
                    return false;
                }
                if(!CardNo.match(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/)){
                    $.alert("您的身份证号码格式不正确");
                    return false;
                }

                $('.btn_want').removeAttr('onclick');
                setTimeout(function(){$(".btn_want").attr("onclick", "saveinfo()")},1000);
				$.post("<?php echo U('Member/save');?>",$('#formf').serialize(),function (data) {
					if(data.result==1){
					    //XB.Tip(data.message);
              $.alert(data.message);
                        setTimeout(function () {
                            window.location.href="<?php echo U('Member/index');?>";
                        },1500);
                    }else{
					//	XB.Tip(data.message);
            $.alert(data.message);
					}
				},'json')
			}

		</script>