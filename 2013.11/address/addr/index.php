<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

include './matches.php';

$matches = new matches();

if(isset($_GET['act']) && $_GET['act'] == 'test'){

    foreach(explode(",", $matches->replace1($_POST['content'])) as $x){
        if(trim($x)=='') continue;

        $result = $matches->matchstr($x);
        echo '////////////////////////////////////////////////////////////////';
        if($result[1])
            echo wrapspan($result);
        else
            echo wrapspan1($result);
    }
    die(0);
}
$conn = new PDO('mysql:host=127.0.0.1;dbname=ydserver;charset=utf8','root','l');
$rs = $conn->query("select psfw from gsjj where bm = '{$_REQUEST['bm']}'");

#if(!$result)  exit();
$row = $rs->fetch();
#if(!$row) exit(0);

$fw = $row[0];


function wrapspan($v){
    return "<span {$v[1]}>{$v[0]}</span>,";
}
function wrapspan1($v){
    return "<span class='b' >{$v[0]}</span>,";
}

$str_wrong =[];
$str_right =[];

$s = $matches->replace1($fw);


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
    //$('#psfw').val($('#div1').html());
    $('#test').click(function(){
        $.post('./index.php?act=test', {'content':$('#psfw').val()}, function(data){
            $('#div1').html(data);
        }
        , 'html'
        );
    });
});
</script>
<style>
.b {color:red;}
</style>
</head>
<body>
<div style="border:dotted gray;"><?php echo $fw;?></div>
<br>
<div id=div1 style="border:dotted gray;width:800px;">
<?php 
foreach(explode(",", $s) as $x){
    if(trim($x)=='') continue;

    $result = $matches->matchstr($x);
    if($result[1])
        echo wrapspan($result);
    else
        echo wrapspan1($result);
}
?>
</div>
<form method=post>
<textarea name=psfw id=psfw style="width:835px;height:270px;">
<?php 

foreach(explode(",", $s) as $x){
    if(trim($x)=='') continue;
    $result = $matches->matchstr($x);
    echo $result[0].',';


}
?>        
        </textarea>
<input type=submit value="Submit">
<input type=button value="Test" id=test>
</form>
</body>
</html>
