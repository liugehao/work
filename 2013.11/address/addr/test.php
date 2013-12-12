<?php
include './matches.php';
$conn = new PDO('mysql:host=127.0.0.1;dbname=ydserver;charset=utf8','root','l');
$rs = $conn->query("select bm,psfw from gsjj ");//where bm = '{$_REQUEST['bm']}'");



function wrapspan($v){
    return "{$v[0]}[{$v[1]}]";
}

$matches = new matches();

while($row = $rs->fetch()){

    echo "$row[0]  ";
    echo "$row[1] \n";
    $str_wrong =[];
    $str_right =[];
    foreach(explode(",", $matches->replace1($row[1])) as $x){
        if(trim($x)=='') continue;

        $result = $matches->matchstr($x);
        if($result[1])
            $str_right[] = wrapspan($result);
        else
            $str_wrong[] = $result[0];

    }
    echo implode("\t",$str_right);
    echo "\n";
    echo "--------------------------\n";
    echo implode("\t",$str_wrong);
    echo "\n";
    echo "--------------------------------------------------\n\n";
}


