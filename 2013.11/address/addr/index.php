<?php
include './matches.php';
$conn = new PDO('mysql:host=127.0.0.1;dbname=ydserver;charset=utf8','root','l');
$rs = $conn->query("select psfw from gsjj where bm = '{$_REQUEST['bm']}'");

#if(!$result)  exit();
$row = $rs->fetch();
#if(!$row) exit(0);

$fw = $row[0];



function wrapspan($v){
    return "<span>{$v[0]}[{$v[1]}]</span>";
}
echo $fw;
echo '<hr>';

$str_wrong =[];
$str_right =[];
$matches = new matches();


foreach(explode(",", $matches->replace1($fw)) as $x){
    if(trim($x)=='') continue;

    $result = $matches->matchstr($x);
    if($result[1])
        $str_right[] = wrapspan($result);
    else
        $str_wrong[] = $result[0];

}
$str='除不派送范围列出';
echo mb_strlen($str);
print preg_match('/[双单][数号]/u', $str);
print !preg_match('/\d/', $str);
if(preg_match('/[双单][数号]/', $str) && !preg_match('/\d/', $str))
                print '5';
     
  var_dump(preg_replace(     '/([一二三四五六七八九十]),/u', '$1.',   '马杭广电路,马杭广电路,兴隆街,贺北第一,二,三,三工业园,利华南村,大沈村，'      ));

preg_match('/,.*?一\.二\.三\.三.*?,/u',"马杭广电路,马杭广电路,兴隆街,贺北第一.二.三.三工业园,利华南村,大沈村，",$a);
var_dump($a);

   /*             
echo                preg_replace_callback('/,(.*?)([一二三四五六七八九十],)+?(.+?),/u',
                create_function('$m',
                preg_replace(),
                '马杭广电路，兴隆街，贺北第一、二、三工业园，利华南村，大沈村，'
                );
                */
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<script type="text/javascript" src="./jquery-2.0.3.min.js" ></script>
<script>
$(function(){
    $('div span').click(function(){
        alert($(this).text());
    });
});
</script>
</head>
<body>
<div>
OK:<?php echo implode("\t", $str_right);?>
</div>
<hr>
<div>
Not matchs:<?php echo implode("\t", $str_wrong) ;?>
</div>
</body>
</html>
