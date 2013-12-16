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

#var_dump($matches->matchstr('广田路双号(90、92、94)'));

$s = $matches->replace1($fw);
echo $s;
foreach(explode(",", $s) as $x){
    if(trim($x)=='') continue;

    $result = $matches->matchstr($x);
    if($result[1])
        $str_right[] = wrapspan($result);
    else
        $str_wrong[] = $result[0];

}
echo preg_match('/([双单][数号]|\d+)/u', "十三纬路(单号)");
echo preg_match('/[双单]号[^\d以]/u', '高尔基路单号199以上、双号212-10以上');
echo preg_match('/[双单]号[^\d以]/u', '民政街单号139以上、双号大号');
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
OK:<?php echo implode("<br>", $str_right);?>
</div>
<hr>
<div>
Not matchs:<?php echo implode("<br>", $str_wrong) ;?>
</div>
</body>
</html>
