<?php
/**
 * 目录操作相关
 * User: huangxinmin
 * Date: 2016/10/25
 * Time: 下午8:45
 */
/**
 * 遍历目录，只遍历最外层
 * @param $path 路径 string
 * @return array 目录下的文件 array
 */
function readDircectory($path)
{
    //打开文件夹
    $handle = opendir($path);
    //读文件夹
    while (($item = readdir($handle)) !== false) {
        if (is_file($path . "/" . $item)) {
            $arr['file'][] = $item;
        } else if (is_dir($path . "/" . $item)) {
            if ($item !== '.' && $item !== "..") {
                $arr['dir'][] = $item;
            }
        }
    }
    closedir($path);
    return $arr;
}