<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <title>上传窗口</title>
    <style>
        body {
            margin: 0px;
            padding: 0px;
            background-color: #F5F5F5;
        }
    </style>
</head>
<body>
    <div id="uploader"></div>
    <script src="/Public/Admin/JS/jquery.min.js"></script>
    <link href="/Public/Admin/JS/EasyUI/easyui.css" rel="stylesheet" />
    <script src="/Public/Admin/JS/EasyUI/jquery.easyui.min.js"></script>
    <script src="/Public/Admin/JS/Plupload/plupload.full.min.js"></script>
    <script src="/Public/Admin/JS/Plupload/plupload.queue.min.js"></script>
    <link href="/Public/Admin/JS/Plupload/css/plupload.queue.css" rel="stylesheet" />
    <script src="/Public/Admin/JS/XB.js"></script>
    <script>
        $(function () {
            $.XB.upload({ "file": "<?php echo ($data['file']); ?>", "path": "<?php echo ($data['Path']); ?>", "ismulti": "<?php echo ($data['ismulti']); ?>", "maxsize": "10000kb", "ext": "gif,jpg,jpeg,bmp,png","url":"/admin.php/Attachment/File/Upload" });
        })
    </script>
</body>
</html>