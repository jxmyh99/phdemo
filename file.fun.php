<?php
/**
 * Created by PhpStorm.
 * User: huangxinmin
 * Date: 2016/10/26
 * Time: 下午9:57
 */
/**
 * 读文件大小并且转为各个单位
 * @param $size 文件大小 number
 * @return float  转换后的数值
 */
function readFileType($size)
{
    //定义数组
    $arr = array("Byte", "KB", "MB", "TB", "GB", "EB");
    $i = 0;

    while ($size >= 1024) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . $arr[$i];
}

/**
 * 创建文件
 * @param $filename 文件名
 * @return string
 *
 */
function createFile($filename){
    //验证文件名的合法性，不能包含/,*,,,<>?
//    $patten = "/[\/,\*,\,,<,>,\?,\|]/";
//    $patten = "/[\w]/";
    if(ckeckFileName($filename)){
        if(basename($filename) === "file"){
            return "文件名不能为空";
        }
//        检测当前文件是否重复
         if(!file_exists($filename)){
            //通过touch($filename)来创建文件
             if(touch($filename)){
                 return "文件创建成功";
             }else{
                 return "文件创建失败";
             }
         }else{
             return "文件已存在,请重命名后创建";
         }
    }else{
        return "文件不合法";
    }
}
/**
 * 重命名操作
 */
function renameFile($oldname,$newname){
    $path = dirname($oldname);
    $name = pathinfo(basename($oldname))['filename'];
    $hz = pathinfo(basename($oldname))['extension'];
//    php如何取文件的后缀名
//    php如何取文件的文件名
    //判断文件名是否合法
    if(ckeckFileName($newname)){
        //判断文件名是否存在相同
        if(!file_exists($path.'/'.$newname)){
            if(rename($oldname,$path.'/'.$newname.'.'.$hz)){
                return "文件重命名成功";
            }else{
                return "文件重命名失败";
            }
        }else{
            return '文件已存在,请重命名后创建';
        }
    }else{
        return "非法文件名，请确定重命名";
    }
}

/**
 * 验证文件名
 * return booler
 */
function ckeckFileName($filename){
    $patten = "/[\/,\*,\,,<,>,\?,\|]/";
    $name = pathinfo(basename($filename))['filename'];
    $hz = pathinfo(basename($filename))['extension'];
    if(preg_match($patten,$name)){
        return false;
    }else{
        return true;
    }
}

/**
 * 删除文件
 */
function delFile($filename){
    if(unlink($filename)){
        $mes = "文件删除成功！";
    }else{
        $mes = "文件删除失败";
    }
    return $mes;
}
/**
 * 下载文件
 */
function downFile($filename){
    header("content-disposition:attachment;filename=".basename($filename));
    header("content-length:".filesize($filename));
    readfile($filename);
}