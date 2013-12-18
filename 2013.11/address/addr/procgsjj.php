<?php
include './matches.php';

$psfw = array();
$bpsfw = array();
$matches = new  matches();
function matchesall($row, $i){
    global $matches, $psfw, $bpsfw;

    $s = $matches->replace1($row[$i]);
    foreach(explode(",", $s) as $x){
        if(trim($x)=='') continue;
        $result = $matches->matchstr($x);
        if($result[1])
            if($i == 1)
                $psfw[$row[3]][]  = array($result[0], $row[0]);
            else
                $bpsfw[$row[3]][]  = array($result[0], $row[0]);
    }

}



$conn = new PDO('mysql:host=127.0.0.1;dbname=ydserver;charset=utf8','root','l');
$rs = $conn->query("SELECT j.bm,j.psfw,j.bpsfw,g.qu FROM ydserver.gsjj j INNER JOIN addr.wdqu g ON g.bm=j.bm ;");
while($row=$rs->fetch()){
    #print "$row[0], $row[3],". mb_strlen($row[1])." ". mb_strlen($row[2])."\n";
    matchesall($row, 1);
    matchesall($row, 2);
}
$psfwfile = fopen('./psfw', 'w');
$bpsfwfile = fopen('./bpsfw', 'w');

fwrite($psfwfile, serialize( $psfw));
fwrite($bpsfwfile, serialize($bpsfw));
fclose($psfwfile);
fclose($bpsfwfile);


