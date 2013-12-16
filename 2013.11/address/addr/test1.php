<?php
include './matches.php';
$str="秋涛北路,43-97单号,287-383,202-222双号,机场路单号,1-51双号1-24,凤起东路,单号1-101双号2-118,太平门直街,单号1-261号双号2-256号,双菱路,单号65-77号双号80-112,凯旋路,单号171号以上双号120以上,艮山西路,单号81号以上,94双号以上,景芳路,137以上单号148以上双号,";


$matches = new matches();
$str= $matches->replace1($str);
foreach(explode(',',$str) as $r){
var_dump($matches->matchstr($r));
}



/*
$s = "秋涛北路,单号43-97号/287-383号,双号202-222号,";

$temp1 = preg_match_all('/(([单双][号数])?\d+)(([\--至到]\d+([单双][号数])?)|(以上|以后|以下))/u', $s, $t);
if($temp1> 0){
    $temp_f = explode($t[0][0], $s);
    echo $temp_f[0];
    foreach($t[0] as $temp){
        echo $temp_f[0] . $temp;
    }
}
([\--至到]\d+)?([单双][号数])?))

var_dump($t);

preg_match_all('/,\d+([\--至到]\d+)?([单双][号数])?(以上|以后|以下)?/u', $str, $tmp);
foreach($tmp[0] as $t){
    
}
$a= preg_replace('/(,)(\d+[\--至到]\d+)?([单双][号数])?/u', '$1$3$2', $str);
$a= preg_replace('/(,)(\d+)([单双][号数])(以上|以后|以下)/u', '$2$1$3', $str);
var_dump($a);
*/
