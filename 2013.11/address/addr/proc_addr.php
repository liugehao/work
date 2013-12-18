<?php
#include './matches.php';
#$conn = new PDO('mysql:host=127.0.0.1;dbname=ydserver;charset=utf8','root','l');
#$rs = $conn->query("SELECT j.bm,j.psfw,j.bpsfw,g.qu FROM ydserver.gsjj j INNER JOIN addr.wdqu g ON g.bm=j.bm ;");

$psfw = unserialize(stream_get_contents(fopen('./psfw', 'r')));
foreach($psfw as $k=>$v){
    print "$k\n";
    foreach($v as $v1)
        print "$v1[0] $v1[1] \n";
    print "---------------------------------------------------\n";
}



