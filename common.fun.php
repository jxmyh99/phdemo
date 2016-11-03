<?php
/**
 * Created by PhpStorm.
 * User: huangxinmin
 * Date: 2016/10/28
 * Time: 下午10:13
 */
/**
 * 信息提示并且跳转地址
 * @param $mes 提示信息 string
 * @param $url 跳转地址 string
 */
function alertMes($mes,$url){
    echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}