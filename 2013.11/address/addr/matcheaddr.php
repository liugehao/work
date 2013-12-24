<?
/*
*用ｇｓｊｊ匹配快递地址
*
*/
class MatchAddr
{
    public $psfw = '';
    public $bpsfw = '';

    public function __construct(){
        if(!file_exists('./psfw') || !file_exists('./bpsfw')){
            echo "file not found.\n";
            return false;
        }
            
        $f = fopen('./psfw', 'r');
        $this->psfw = unserialize(stream_get_contents($f));
        fclose($f);
        $f = fopen('./bpsfw', 'r');
        $this->bpsfw = unserialize(stream_get_contents($f));
        fclose($f);
        
    }
    function QuExists($qx){
        if(!array_key_exists($qx, $this->psfw) && !array_key_exists($qx, $this->bpsfw) )
            return false;
        return true;
    }
    function Match($qx, $addr){
        if(!$this->QuExists($qx))
            return false;
        $ps = $this->Match1($qx, $addr);
        $bps = $this->Match1($qx, $addr, 'bpsfw');
        
        if($bps[0] === 'all' && $ps) return array('p', $ps[1]);
        if($bps) return  array('b', $bps[1]);
        if($ps) return  array('p', $ps[1]);
        return false;
        
    }    
    
    function Match1($qx, $addr, $lb='psfw'){
        $lb = $lb =='psfw' ?$this->psfw:$this->bpsfw;
        if(!array_key_exists($qx, $lb)) return false;
        foreach($lb[$qx] as $reg){
            if($reg[0] === 'all')
                return $reg;
            if(preg_Match('/\(/u', $reg[0])){
                $tmp = $this->MatchBracket($reg, $addr);
            }
            else{
                $tmp = $this->MatchNoBracket($reg, $addr);
            }
            if($tmp)
                return $reg;
        }
        return false;
    }
    
    function MatchBracket($reg, $addr){
        if(preg_Match('/^([^(]*?)\(([^)]*?)\)$/u', $reg[0], $tmp)){
            if($tmp[1] =='' || $tmp[1] == Null)
                echo "$tmp[1] \t $addr \t $reg[0] \t $reg[1]";
            if(strpos($addr, $tmp[1]) !==false){
                if(preg_Match('/'.$tmp[1].'.*?(\d+)/u', $addr, $tmp1) && count($tmp1)>0){
                
                    $doorplate =  $tmp1[1];
                    foreach(explode('、', $tmp[2]) as $tmp3){
                
                        if(preg_Match('/^[单双][号数]$/u', $tmp3) && $this->OddOrEven($tmp3, $doorplate)) return $reg;
                        
                        if(preg_Match('/^[单双][号数](\d+)-(\d+)$/u', $tmp3, $tmp4) && $this->OddOrEven($tmp3, $doorplate) && $tmp4[1] <= $doorplate && $tmp4[2] >= $doorplate) return $reg;
                        if(preg_Match('/^(\d+)-(\d+)$/u', $tmp3, $tmp4) &&  $tmp4[1] <= $doorplate && $tmp4[2] >= $doorplate ) return $reg;
                       
                        
                        if(preg_Match('/^[单双][号数](\d+)(以上|以后|以下|以前)$/u', $tmp3, $tmp4) && $this->OddOrEven($tmp3, $doorplate) && $this->UpOrDown($tmp3, $doorplate)) return $reg;
                        
                        if(preg_Match('/^(\d+)(以上|以后|以下|以前)$/u', $tmp3, $tmp4) && $this->UpOrDown($tmp3, $doorplate)) return $reg;
                    
                    }
                }
            }            
        }
        return false;
    }
    
    function UpOrDown($upordown, $doorplate){
    #var_dump($upordown);
        if(preg_Match('/(\d+)(以上|以后)/u', $upordown, $tmp) && $doorplate >= $tmp[1]) return true;
        if(preg_Match('/(\d+)(以前|以下)/u', $upordown, $tmp) && $doorplate <= $tmp[1]) return true;
        return false;
        
    }
    function OddOrEven($oe, $doorplate){
        $doorplate_oe = $doorplate % 2;
        if(preg_Match('/[单][号数]/u', $oe) && $doorplate_oe)            return true;
        if(preg_Match('/[双][号数]/u', $oe) && !$doorplate_oe)            return true;
        return false;
    }
    
    function MatchNoBracket($reg, $addr){
        if(strpos($addr, $reg[0]) !==false)
            return $reg;
        return false;
    }
}

/*
$t = new MatchAddr();
$t1 = $t->MatchBracket(array('沪太路(单号5365-6526)','200547'), '沪太路5369');
var_dump($t1);

var_dump($t->Match('310113','沪太路5369'));
*/
