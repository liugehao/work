<?
/*
*用ｇｓｊｊ匹配快递地址
*
*/
class MatchAddr
{
    private $psfw = '';
    private $bpsfw = '';

    public function __construct(){
        /*
        $f = fopen('./psfw', 'r');
        $this->psfw = unserialize(stream_get_contents($f));
        fclose($f);
        $f = fopen('./bpsfw', 'r');
        $this->bpsfw = unserialize(stream_get_contents($f));
        fclose($f);
        */
    }
    
    function Match($qx, $addr){
        $ps = $this->Match1($qx, $addr);
        $bps = $this->Match1($qx, $addr);
    }    
    
    function Match1($qx, $addr, $lb = 'psfw'){
        foreach($this->$$lb[$qx] as $reg){
            if(preg_Match('/\(/u', $reg[0]))
                $tmp = $this->MatchBracket($reg, $addr);
            else
                $tmp = $this->MatchNoBracket($reg, $addr);
            if($tmp)
                return $tmp;
        }
        return false;
    }
    
    function MatchBracket($reg, $addr){
        if(preg_Match('/^([^(]*?)\(([^)]*?)\)$/u', $reg[0], $tmp)){
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
    var_dump($upordown);
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


$t = new MatchAddr();
$t1 = $t->MatchBracket(array('沪太路(单号5365-6526)','200547'), '沪太路5369');
var_dump($t1);

