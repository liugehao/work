<?
include './matcheaddr.php';

$t = new MatchAddr();
$conn = new PDO("mysql:host=127.0.0.1;dbname=addr", "root", "l", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));



function procall($strqu){
    global $conn, $t, $wf;
    
    
    if(!$t->QuExists($strqu)){
        echo "$strqu, no regex!\n";
        fwrite($wf, "$strqu, no regex!\n");
        fflush($wf);
        return ;
    }
    $rs = $conn->query("SELECT a.num, w.qu,a.addr, s.pj FROM wdqu w INNER JOIN sd s ON s.pj=w.`bm` INNER JOIN address a ON a.num=s.`num` WHERE w.qu={$strqu} LIMIT 10000 " );
    $xx = array_fill(0, 3, 0);
    #var_dump($t->psfw[$strqu]);
    
    while($row = $rs->fetch()){
        #echo "{$row[2]}\n";
        $result = $t->Match($row[1], $row[2]);
        
        if($result === false)
            $xx[0] += 1;

        if(is_array($result) && $result[0] == 'p')
            $xx[1] += 1;
        if(is_array($result) && $result[0] == 'b')
            $xx[2] += 1;
        
    }
    echo "$strqu, 不确定: {$xx[0]},  不派送: {$xx[2]},  派送: {$xx[1]}\n";
        fwrite($wf, "$strqu, 不确定: {$xx[0]},  不派送: {$xx[2]},  派送: {$xx[1]}\n");
        fflush($wf);
}
//var_dump($t->psfw['211402']);die();
/*
$wf = fopen('./fw.txt', 'w');
procall('125000');
         

foreach($t->psfw as $qu=>$v){
    foreach($v as $v1){
        if(strpos($v1[0],'吴中路') !==false) 
            echo $qu." ".$v1[1]. " ".$v1[0]."\n";
    }
}
die();
*/
if($t === false){
     echo "no content\n";
     die();
}

$rs = $conn->query("SELECT DISTINCT qu FROM wdqu;");
$wf = fopen('./fw.txt', 'w');
foreach($rs->fetchAll() as $row)
     procall($row[0]);

