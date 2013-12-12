<?php
include './matches.php';
$str = '马杭广电路,马杭广电路,兴隆街,贺北第一,二,三,三工业园,利华南村,大沈村，';


#var_dump(preg_replace('/,?([一二三四五六七八九十]),?/uU','.$1.',$str));


preg_match('/(,?[一二三四五六七八九十]+?,?)+?/uU', $str,$tmp);
var_dump($tmp);

preg_match('/,.{1,3}?一,二,三,三.*?,/u', $str, $tmp);
var_dump($tmp);
