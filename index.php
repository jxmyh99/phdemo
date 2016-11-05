<?php
require_once "dir.fun.php";
require_once "file.fun.php";
require_once "common.fun.php";
//解决时间差
//date_default_timezone_set('prc');
$path = "file";
$info = readDircectory($path);
$currentPath = $path."/";
//print_r($currentPath);
//接收给来的参数
$act = $_REQUEST['act'];
$filename = $_REQUEST['filename'];
//跳转地址
//$redirect="index.php?path={$path}";
$redirect = "index.php?path={$path}";
//判断是否是创建文件
if($act == 'createFile'){
   $mes =  createFile($currentPath.$filename);
    alertMes($mes,$redirect);
}elseif($act === 'showContent'){
    //查看文件内容操作
    $content = file_get_contents($filename);
//    echo "<textarea cols='100' rows='10' readonly>{$content}</textarea>";
    //高亮显示代码
    if(strlen($content)){
        $newContent = highlight_string($content,true);
    //    highlight_file($filename);
        $str = <<<EOF
        <div style='width:100%;height:200px;overflow:auto;background:pink'>{$newContent}</div>

EOF;
     echo $str;
    }else{
        alertMes("文件没有内容,请先编辑再查看！",$redirect);
    }
}elseif($act === "editContent"){
//    echo $filename;
    $content = file_get_contents($filename);
    $str = <<<EOF
  <form action='index.php?act=docEdit' method='post'>
    <textarea name='content' cols='190' rows='10'>{$content}</textarea>
    <input type='hidden' name='filename' value='{$filename}'/>
    <input type='submit' value="修改文件内容"/>
  </form>
EOF;
    echo $str;
}elseif($act === "docEdit"){
//    修改文件 内容
//    重复n次了
    $content = $_REQUEST['content'];
    if(file_put_contents($filename,$content)){
        $mes = "文件修改成功";
    }else{
        $mes="文件修改失败";
    }
    alertMes($mes,$redirect);


}elseif($act==="renameFile"){
//    重命名文件
//    renameFile()

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Insert title here</title>
    <link rel="stylesheet" href="cikonss.css" />
    <script src="jquery-ui/js/jquery-1.10.2.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
    <script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css"  type="text/css"/>
    <style type="text/css">
        body,p,div,ul,ol,table,dl,dd,dt{
            margin:0;
            padding: 0;
        }
        a{
            text-decoration: none;
        }
        ul,li{
            list-style: none;
            float: left;
        }
        #top{
            width:100%;
            height:48px;
            margin:0 auto;
            background: #E2E2E2;
        }
        #navi a{
            display: block;
            width:48px;
            height: 48px;
        }
        #main{
            margin:0 auto;
            border:2px solid #ABCDEF;
        }
        .small{
            width:25px;
            height:25px;
            border:0;
        }
    </style>
    <script type="text/javascript">
        function show(dis){
            document.getElementById(dis).style.display="block";
        }
        function delFile(filename,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFile&filename="+filename+"&path="+path;
            }
        }
        function delFolder(dirname,path){
            if(window.confirm("您确定要删除嘛?删除之后无法恢复哟!!!")){
                location.href="index.php?act=delFolder&dirname="+dirname+"&path="+path;
            }
        }
        function showDetail(t,filename,dom){
            var ext = filename.split('.')[1];
            var that = $(dom);
            if(ext == "png" || ext == "gif" || ext == "jpg" || ext == "jpge"){
                $("#showDetail").html("<img src="+filename+" />");
            }else{
//                如何把php获取的文件内容传给js来显示
                var text = that.next('div').html();
                $("#showDetail").html(text);
            }
            $("#showDetail").dialog({
                height:"auto",
                width: "auto",
                position: {my: "center", at: "center",  collision:"fit"},
                modal:false,//是否模式对话框
                draggable:true,//是否允许拖拽
                resizable:true,//是否允许拖动
                title:t,//对话框标题
                show:"slide",
                hide:"explode"
            });
        }
        function goBack($back){
            location.href="index.php?path="+$back;
        }
    </script>
</head>

<body>
<div id="showDetail"  style="display:none"></div>
<h1>慕课网-在线文件管理器</h1>
<div id="top">
    <ul id="navi">
        <li><a href="index.php" title="主目录"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-home"></span></span></a></li>
        <li><a href="#"  onclick="show('createFile')" title="新建文件" ><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-file"></span></span></a></li>
        <li><a href="#"  onclick="show('createFolder')" title="新建文件夹"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-folder"></span></span></a></li>
        <li><a href="#" onclick="show('uploadFile')"title="上传文件"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-upload"></span></span></a></li>
        <li><a href="#" title="返回上级目录" onclick="goBack('<?php echo $back;?>')"><span style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span class="icon-arrowLeft"></span></span></a></li>
    </ul>
</div>
<form action="index.php" method="post" enctype="multipart/form-data">
    <table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">
        <tr id="createFolder"  style="display:none;">
            <td>请输入文件夹名称</td>
            <td >
                <input type="text" name="dirname" />
                <input type="hidden" name="path"  value="<?php echo $path;?>"/>
                <input type="hidden" name="act" value="createDir">
                <input type="submit"  value="创建文件夹"/>
            </td>
        </tr>
        <tr id="createFile"  style="display:none;">
            <td>请输入文件名称</td>
            <td >
                <input type="text"  name="filename" />
                <input type="hidden" name="path" value="<?php echo $path;?>"/>
                <input type="hidden" name="act" value="createFile">
                <input type="submit"  value="创建文件" />
            </td>
        </tr>
        <tr id="uploadFile" style="display:none;">
            <td >请选择要上传的文件</td>
            <td ><input type="file" name="myFile" />
                <input type="submit" name="act" value="上传文件" />
            </td>
        </tr>
        <tr>
            <td>编号</td>
            <td>名称</td>
            <td>类型</td>
            <td>大小</td>
            <td>可读</td>
            <td>可写</td>
            <td>可执行</td>
            <td>创建时间</td>
            <td>修改时间</td>
            <td>访问时间</td>
            <td>操作</td>
        </tr>
        <?php
            if($info['file']){
                $i = 1;
                foreach ($info['file'] as $val){
                    // 当前文件
                    $currentPathVal = $currentPath.$val;
                    //当前文件大小
                    $size = filesize($currentPathVal);
        ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $val ?></td>
                        <td>
                            <?php $src = filetype($currentPathVal) == "file" ? "file_ico.png" : "folder_ico.png" ?>
                            <img src="images/<?php echo $src ?>" alt="">
                        </td>
                        <td>
                            <?php echo readFileType($size) ?>
                        </td>
                        <td>
                            <?php $src = is_readable($currentPathVal) ? "correct.png" : "error.png"; ?>
                            <img src="images/<?php echo $src ?>" width="28" height="28" alt="">
                        </td>
                        <td>
                            <?php $src = is_writable($currentPathVal) ? "correct.png" : "error.png"; ?>
                            <img src="images/<?php echo $src ?>" width="28" height="28" alt="">
                        </td>
                        <td>
                            <?php $src = is_executable($currentPathVal) ? "correct.png" : "error.png"; ?>
                            <img src="images/<?php echo $src ?>" width="28" height="28" alt="">
                        </td>
                        <td>
                            <?php echo date("Y-m-d H:i:s",filectime($currentPathVal)) ?>
                        </td>
                        <td>
                            <?php echo date("Y-m-d H:i:s",filemtime($currentPathVal)) ?>
                        </td>
                        <td>
                            <?php echo date("Y-m-d H:i:s",fileatime($currentPathVal)) ?>
                        </td>
                        <td>
                            <?php
//                                先得到后缀名
                                $ext = strtolower(end(explode(".",$currentPathVal)));
                                $imgExt = array("gif","png","jpg","jpge");
                                if(in_array($ext,$imgExt)){
                                    ?>
                                   <a href="javascript:;" onclick="showDetail('<?php echo $val ?>','<?php echo $currentPathVal ?>',this)"><img class="small" src="images/show.png"  alt="" title="查看"/></a>|

                            <?php
                                }else{
                            ?>
<!--多了个1-->
                                    <a href="javascript:;" onclick="showDetail('<?php echo $val ?>','<?php echo $currentPathVal ?>',this)"><img class="small" src="images/show.png"  alt="" title="查看"/></a>|
                                    <div style="display: none;"><?php echo highlight_string(file_get_contents($currentPathVal)) ?></div>
                            <?php
                                }
                            ?>
                            <a href="index.php?act=editContent&filename=<?php echo $currentPathVal;?>"><img class="small" src="images/edit.png"  alt="" title="修改"/></a>|
                            <a href="index.php?act=renameFile&filename=<?php echo $currentPathVal;?>"><img class="small" src="images/rename.png"  alt="" title="重命名"/></a>|
                            <a href="index.php?act=copyFile&filename=<?php echo $currentPathVal;?>"><img class="small" src="images/copy.png"  alt="" title="复制"/></a>|
                            <a href="index.php?act=cutFile&filename=<?php echo $currentPathVal;?>"><img class="small" src="images/cut.png"  alt="" title="剪切"/></a>|
                            <a href="#"  onclick="delFile('<?php echo $currentPathVal;?>','<?php echo $path;?>')"><img class="small" src="images/delete.png"  alt="" title="删除"/></a>|
                            <a href="index.php?act=downFile&filename=<?php echo $currentPathVal;?>"><img class="small"  src="images/download.png"  alt="" title="下载"/></a>

                        </td>
                    </tr>
        <?php
                    $i++;
                }
            }
        ?>
    </table>
</form>

</body>
</html>