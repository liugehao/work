<?
class matches
{
    private $str1 = '(创业园|工业园|博物馆|局|[一二三四五]期|[一二三四五六七八九十]中|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|大[楼厦院]|学校|分校|宿舍|屯|庄|村|庄|镇|乡|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|产业园区|集团|工业区|工业园区|开发区|体育馆|医院|汽车城|公寓|派出所|办公楼|综合楼|住宅楼|高中|法院|武装部|大队|软件园|园区|检察院|[一二三四五六七八九十百千万零]+区|写字楼|家属楼|商场|宿舍楼|加油站|宾馆|[旅酒饭][店馆]|部队|校区|消防队|保健院|防疫站|分行|菜场|派出所|世界|商务楼|会馆|[一二三四五六七八九十百千万零]段|城|厂|街|胡同|弄|路|道|巷|里|矿)';
    private $debug = 0;
    function replace1($str){

        if($str == '') {return '';}
        $tmp = preg_replace(array('/&gt;/u', '/&lt;/u'), array( '>','<'), $str);
        $tmp = preg_replace(array('/(\s*)?([\(（\[])/u', '/[\]\)））]/u', '/【更新时间.*?】/u'), array("(","),", ""), $tmp);
        $tmp = preg_replace('/[\s\r\n·\/、,，.;:。；：_|#◇◆《》★　●【】]|<.*?>|&\w*?;/u', ",", $tmp);
        
        $tmp = preg_replace('/\(([^)]*)\)/u', '$1', $tmp);
        $tmp = preg_replace('/[\-－—～]+/u', '-', $tmp);
        $tmp = preg_replace('/,(街|胡同|弄|路|道|巷|里),/u', '$1,', $tmp);

        
        $tmp = preg_replace('/\,+[\-－～]\,+/u', '-', $tmp);
        
        
        #var_dump($tmp);

        $tmp = preg_replace_callback('/[每]?周[一二三四五六日].*?派送/u',
                create_function('$matches',
                'return preg_replace("/,/u", "",$matches[0]);'),
                $tmp);

        /*
        #除不派送范围	其它全部派送
        $tmp = preg_replace_callback('/除不派送范围.{0,3}?(其它?)全[部]?派[送]?/u',
                create_function('$matches',
                'return preg_replace("/,/u", "",$matches[0]);'),                
                $tmp);
        */
        
        $tmp = preg_replace('/\(每?周[一二三四五六日].*?派送\)/u', '', $tmp);
        $tmp = preg_replace(array('/(\d+以)内/u','/(\d+以)外/u'), array('$1下','$1上'), $tmp);
        
        
        #var_dump($tmp);
        #/(([单双][号数])?\d+)(([\--至到]\d+([单双][号数])*?)|(以上|以后|以下))/u
        #$tmp = preg_replace('/,(([^单双][^号数])?\d+)(([\--至到]\d+([单双][号数])*?))/u'
        #|(以上|以后|以下))/u
        
        #号/双
        #春东路327-689(单号)、508-950(双号)
        $tmp= preg_replace('/(\d+[\--至到]\d+)\(([单双][号数])\)(,)/u', '$2$1$3', $tmp);
        #$tmp = preg_replace('/(\d+)号([双单][数号]),/u', '$1$2,' , $tmp);
        $tmp = preg_replace('/([^\d\-])(\d+)(号?院|号?楼|栋|幢|门|座|弄|号)/u', '$1$2' , $tmp);
        $tmp = preg_replace('/(\d+)(号?院|号?楼|栋|幢|门|座|弄|号)/u', '$1' , $tmp);
        #var_dump($tmp);
        $tmp = preg_replace('/,{2,}/', ",", $tmp);
        $tmp = preg_replace('/\((,?[一二三四五六七八九十],?)\)/u', "、$1、", $tmp);
if($this->debug) echo $tmp.' r3 <br>'; 
        $tmp= preg_replace('/([单双])(\d+)/u', '$1号$2', $tmp);
        $tmp= preg_replace('/(\d+[\--至到]\d+)([单双][号数])(,)/u', '$2$1$3', $tmp);
        
        
        $tmp= preg_replace('/(,)(\d+[\--至到]\d+)([单双][号数])/u', '、$3$2$1', $tmp);
if($this->debug) echo $tmp.' r4 <br>';
        $tmp= preg_replace('/(,)([单双][号数][^,\d]*?),/u', '、$2,', $tmp);
if($this->debug) echo $tmp.' r5 <br>';
        $tmp= preg_replace('/(\d+)([单双][号数])(以上|以后|以下)(,)/u', '$2$1$3$4', $tmp);
        $tmp= preg_replace('/(,)(\d+)([单双][号数])(以上|以后|以下)/u', '$1$3$2$4', $tmp);
        if($this->debug) echo $tmp.' r5_2 <br>';
        $tmp= preg_replace('/(\d+)(以上|以后|以下)([单双][号数])(,)/u', '$3$1$2$4', $tmp);
        $tmp= preg_replace('/(,)(\d+)(以上|以后|以下)([单双][号数])/u', '$1$4$2$3', $tmp);
        if($this->debug) echo $tmp.' r5_3 <br>';
        $tmp= preg_replace('/(,)[单双](\d+)/u', '$1号$2', $tmp);
        #(单1-79___双2-58)
        
        #
        #$tmp= preg_replace('/(\d+)(单号|双号)、/u', '$2$1,', $tmp);
        $tmp= preg_replace('/(\d+)([单双][数号])/u', '$1、$2', $tmp);
         if($this->debug) echo $tmp.' r5_3_1 <br>';
        $tmp= preg_replace('/(以上|以后|以下)([单双][号数])/u', '$1、$2', $tmp);
        if($this->debug) echo $tmp.' r5_3_2 <br>';
        $tmp= preg_replace('/(以上|以后|以下)(\d+)/u', '$1、$2', $tmp);
        if($this->debug) echo $tmp.' r5_4 <br>';
        
        $tmp = preg_replace('/(\d+)[\-—至到]+?(\d+)/u', '$1-$2', $tmp);
        if($this->debug) echo $tmp.' r5_5 <br>';

        $tmp = preg_replace('/、+/', '、', $tmp);
        
        
        
        
        #1 2 3 4 5 6村        
        if(preg_match_all('/([\d],)+\d[村区段]/u', $tmp, $tmp1)){
            foreach(array_unique($tmp1[0]) as $tmp2){
                $tmp= str_replace($tmp2, preg_replace(array('/1|１/u','/2|２/u','/3|３/u','/4|４/u','/5|５/u','/6|６/u','/7|７/u','/8|８/u','/9|９/u','/10|１０/u'), array('一','二','三','四','五','六','七','八','九','十' ),$tmp2), $tmp);
            }
            #var_dump($tmp);
        }
        #静安里一区四区六区
        if(preg_match_all('/([一二三四五六七八九十][区段村][,]?)+/u', $tmp,$tmp1)){
            foreach($tmp1[0] as $tmp2){
                if(preg_match_all('/([一二三四五六七八九十])([街|胡同|弄|路|道|巷|里|矿|区|段|村|区|队]),?/u', $tmp2,$tmp3) > 1){
               
                    $tmp4 = implode(',', $tmp3[1]).$tmp3[2][0].','; 
                    $tmp = str_replace($tmp2, $tmp4, $tmp);

                }
            }
        }
        #var_dump($tmp);
        #繁荣上中下街
        if(preg_match_all('/([上中下]{2,})(街|胡同|弄|路|道|巷|里|矿|区|段|村|区|队)/uU', $tmp,$tmp1)){
            for($i=0;$i<count($tmp1[1]);$i++){
                $tmp = str_replace($tmp1[0][$i], implode(",",preg_split('/(?<!^)(?!$)/u', $tmp1[1][$i])).$tmp1[2][$i], $tmp);
            }
        }
        #荥兴路西一二三段 
        #var_dump($tmp);
        if(preg_match_all('/([^一二三四五六七八九十,])([一二三四五六七八九十]{2,})(街|胡同|弄|路|道|巷|里|矿|区|段|村|区|队)/uU', $tmp,$tmp1)){   
            
            for($i=0;$i<count($tmp1[2]);$i++){
           
                if(preg_match('/^[一二三四五六七八九十]/u', $tmp1[0][$i])) {
                    
                    continue;
                }
                $tmp = str_replace($tmp1[0][$i], $tmp1[1][$i].implode(",",preg_split('/(?<!^)(?!$)/u', $tmp1[2][$i])).$tmp1[3][$i], $tmp);
            }
        }
        #var_dump($tmp);
        #荥兴路西一,二,三段 繁荣上,中,下街
        #var_dump($tmp);
        #echo "<hr>";
        #if(preg_match_all('/,(([^,]*?([一二三四五六七八九十],)+.*?),)/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        #var_dump($tmp);echo '<hr>';var_dump($tmp1);
        if(preg_match_all('/([^,一二三四五六七八九十]*?(,?[一二三四五六七八九十],?)+[^,]*?),/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        #var_dump($tmp1);

        if(preg_match_all('/([^,上中下以]*?(,?[上中下],?)+[^,]*?),/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        #if(preg_match_all('/,(([^,]*?[^以]([上中下],)+.*?),)/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        #var_dump($tmp1);
        #if(preg_match_all('/,(([^,]*?([东西北],)+.*?),)/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        if(preg_match_all('/([^,东西北以]*?(,?[东西北],?)+[^,]*?),/u', $tmp,$tmp1, PREG_PATTERN_ORDER))$tmp = $this->replace2($tmp, $tmp1);
        #var_dump($tmp1);
        #var_dump($tmp);echo "<hr>";
        
        if($this->debug) echo $tmp.' r6 <br>';
        if(preg_match_all('/\((.*?)\)/u', $tmp, $tmp1)){
            foreach($tmp1[1] as $tmp2){
                if(!preg_match('/\d/', $tmp2)){
                    foreach($tmp1[1] as $tmp3){
                        
                        if(preg_match_all('/'.$this->str1.'/u',$tmp3)> 0){
                            
                            $tmp = str_replace("({$tmp3})", ",$tmp3", $tmp);
                        }
                    }
                }
                
            }
        }
        if($this->debug) echo $tmp.' r7 <br>';
        
        #$tmp = preg_replace_callback('/,(.*?)([一二三四五六七八九十],)+?(.+?),/u',);
        $tmp = preg_replace('/,((单号|双号|甲|乙|丙|丁)?\d+[\-号]?)/', "、$1", $tmp);
        #$tmp = preg_replace('/,([双单][数号])/u', '、$1' , $tmp);
        
        $tmp = preg_replace('/除不[派收]送范围.{0,3}?(其它?)全[部]?[派收][送]?/u', '全境派送', $tmp);
        $tmp = preg_replace('/除[派收]送范围.{0,3}?(其它?)全[部]?不[派收][送]?/u', '全境不派送', $tmp);
        
        $tmp = preg_replace_callback('/[\[\(].*?[\)\]]/u', 
                create_function(
                    '$matches',
                    'return preg_replace(\'_[/、,，.;:。；：|#,]_u\',"、", $matches[0] );'
                ),
                $tmp
            );
        if($this->debug) echo $tmp.' r9 <br>';
        return $tmp;
    }
    function replace2($tmp, $tmp1){
        
        #var_dump($tmp1);
        foreach($tmp1[1] as $tmp2){

            if(preg_match_all('/[东西北]/u', $tmp2)< 2 && preg_match_all('/[上中下]/u', $tmp2)<2 && preg_match_all('/[一二三四五六七八九十]/u', $tmp2)<2) continue;
            if(!preg_match('/[东西北],[东西北]/u', $tmp2) && !preg_match('/[上中下],[上中下]/u', $tmp2) && !preg_match('/[一二三四五六七八九十],[一二三四五六七八九十]/u', $tmp2)) continue;
            
            if(preg_match('/^[一二三四五六七八九十],[一二三四五六七八九十]/u', $tmp2)){
            
                #$tmp = str_replace($tmp2, implode(',', $tmp2).',', $tmp);
                continue;
            }
            if(preg_match('/^[上中下],[上中下]/u', $tmp2)) {
                #$tmp = str_replace($tmp2, implode(',', $tmp2).',', $tmp);
                continue;
            }
            
            if(preg_match('/^[东西北],[东西北][^至到]/u', $tmp2)) {
            
                #$tmp = str_replace($tmp2, implode(',', $tmp2).',', $tmp);
                continue;
            }
            
            preg_match('/^([^一二三四五六七八九十]+)((,?[一二三四五六七八九十],?)+)([^一二三四五六七八九十]+)$/u', $tmp2, $tmp3);

            if(count($tmp3) ==0 || mb_strlen($tmp3[2]) <2)
                preg_match('/^([^东西北]+)((,?[东西北],?)+)([^东西北]+)$/u', $tmp2, $tmp3);

            if(count($tmp3) ==0 || mb_strlen($tmp3[2]) <2)
                preg_match('/^([^上中下]+)((,?[上中下],?)+)([^上中下]+)$/u', $tmp2, $tmp3);

            if(count($tmp3) ==0 || mb_strlen($tmp3[2]) <2)
                continue;
                /*
            if(preg_match('/[东西北][一二三四五六七八九十][东西北]?/u', $tmp3[2]))
                preg_match('/^([^一二三四五六七八九十]+)((,?[一二三四五六七八九十],?)+)([^一二三四五六七八九十]+)$/u', $tmp2, $tmp3);
            if(preg_match('/[上中下][一二三四五六七八九十][上中下]?/u', $tmp3[2]))
                preg_match('/^([^一二三四五六七八九十]+)((,?[一二三四五六七八九十],?)+)([^一二三四五六七八九十]+)$/u', $tmp2, $tmp3);
            if(preg_match('/[东西北][东西北][东西北]?/u', $tmp3[2]))
                preg_match('/^([^一二三四五六七八九十]+)((,?[一二三四五六七八九十],?)+)([^一二三四五六七八九十]+)$/u', $tmp2, $tmp3);
                */
               
            $tmp4 = explode(',', $tmp3[2]);
            $tmp5 = array();
            foreach($tmp4 as $tmp7)
                $tmp5[] = $tmp3[1].$tmp7.$tmp3[4];
            
            $tmp8 = implode(',', $tmp5);

            $tmp = str_replace($tmp3[0], $tmp8, $tmp);

            
        }
        return $tmp;
    }
    function matchstr($str){
        if(preg_match('/全境[不]?派送/u', $str)) return array('all', '0');
        
        if($this->debug) echo $str." m1 <br>";
        $str = preg_replace('/全境$/u', '', $str);
        if(preg_match('/^(号?以[上后下]|不含)/u', $str)) return array($str, false);
        if(preg_match('/(号?以[上后下]|不含).*?\(/u', $str)) return array($str, false);
        if(preg_match('/^\(.*\)$/u', $str)) return array($str, false);
        
        
        
        if(preg_match('/\d+-\.*?\(/u', $str)) return array($str, false);
        if(preg_match_all('/\(/u', $str)>1) return array($str, false);
        if(preg_match('/^[\-—至到]\d+/u', $str)) return array($str, false);
        if(preg_match('/区[域间]/u', $str))    return array($str, false);
        if(preg_match('/[东南西北][至到]/u', $str))    return array($str, false);
        if(preg_match('/以[东南西北]|除此号/u', $str))    return array($str, false);
        if(preg_match('/除不派送范围.{0,3}?其它全[部]?派[送]?/u', $str))
        if(count(preg_match('/'.$this->str1.'/u', $str)) >1)  return array($str, false);
        if(preg_match('/[^\d][\-到][^\d]/u', $str))    return array($str, false);
        
        if(preg_match('/\(.*?[^\d单双号以下上\-、].*?\)/u', $str)) return array($str, false);
        
        $str = preg_replace('/全程$|全部$|全路段$|全段$/u', '', $str);
        
        
        if($str == '详情请点击') return array($str, false);
        if(preg_match('/^全境(不?)派送$/u', $str)) return array($str, '9');
        if(preg_match_all('/(街|胡同|弄|路|道|巷|里)/u', $str)>1 ) return array($str, false);
        
        if(preg_match('/[城镇县市][区城](内|$)|县城区/u', $str)) return array($str, false);
        if(preg_match('/城区$/u', $str)) return array($str, '9_1');
        if($this->debug) echo $str." m2 <br>";
        if(preg_match('/(\d+)-(\d+)/u', $str, $m)){            
            if((int)$m[1] > (int)$m[2]) $str = preg_replace('/(\d+)-(\d+)/u', '$2-$1', $str); #return array($str, false);            
        }
        if(preg_match('/'.$this->str1.'$/u', $str)) {
            if(preg_match('/([双单][数号]|\d+)([^\-以、][^上后下])/u', $str)) return array($str, false);
            return array($str, '_2');
        }
        if(mb_strlen($str)<6 && mb_strlen($str)>1 && !preg_match('/(.*?)\(/u', $str) && !preg_match('/\d+/u', $str))    return array($str, '<6');
        /*
        if(preg_match('/[^至到]'.$this->str1.'$/u', $str) && !preg_match('/[()]/u', $str)){        
            return array($str, '2');
        }
        
        $temp1 = preg_match_all('/(([单双][号数])?\d+)(([\--至到]\d+([单双][号数])*?)|(以上|以后|以下))/u', $str, $t);
        if($temp1> 0){

            $temp1 = array();

            $temp_f = explode($t[0][0], $str);
            foreach($t[0] as $temp){
                $temp1[] =  $temp;
            }

            return array($temp_f[0].implode('、', $temp1), '6');
        }*/
        #echo $str."<br>";
        if(preg_match('/[双单]号\(/u', $str)) return array($str, false);
        if(preg_match('/单号\d+.双号\d+.[\-至]单号\d+.双号\d+/u', $str)) return array($str, false);
        if(preg_match('/双号\d+.单号\d+.[\-至]双号\d+.单号\d+/u', $str)) return array($str, false);
        
        if(preg_match('/\(.*?\)$/u', $str)){
            #if(preg_match('/([双单][数号]|\d+)/u', $str)) return array($str, '_1_1');
            if(preg_match('/\(.*?其它/u', $str)) return array($str, false);
            if(preg_match('/(.*?)\(/u', $str, $a) && mb_strlen($a[1]) <2) return array($str, false);
            
            if(preg_match('/\d{5,}/u', $str)) return array($str, false); 
            if(preg_match('/\(.*?[含除包]/u', $str)) return array($str, false);
            if(preg_match('/([双单][数号]|\d+)([\-以、][上后下])?/u', $str)){
            #if(preg_match('/中山南一路/u',$str)) echo $str;
                if(preg_match('/分部/u', $str)) return array($str, false);
                return array(preg_replace('/、?([双单][数号]\d?|\d+)(.*)$/u','$1$2', $str), '_1_2');
                #return array(preg_replace('/、?([双单][数号]\d?|\d+)(.*?$|$)/u','$1$2', $str), '_1_2');
            }
            
            if(preg_match('/\(.*?(街|胡同|弄|路|道|巷|里)/u', $str)) return array($str, false);
            //if(preg_match('/[^\d]\d{1,5}(号|栋)?(以上|以后|以下|([-到至]\d{1,5}(号|栋)?))?\)$/u', $str))
            //    return array($str, '3');
            //if(preg_match('/[^\d]\d{1,5}(号)?(以上|以后|以下|([-到至]\d{1,5}号?))?([双单][数号])?\([含除](此号)?\)$/u', $str))
            //    return '5';
            if($this->debug) echo $str." m3 <br>";
        }else{
            #单号3155、双号3050至单号3535、双号3550
            
            
            if($this->debug) echo $str." m4 <br>";
            //if(preg_match('/[()]/u', $str)) return false;
            //if(preg_match('/[^\d]\d{1,5}(号|栋)?(以上|以后|以下|([-到至]\d{1,5}(号|栋)?))?([双单][数号])?$/u', $str)){
            //    return array($str, '4');}
            //if(preg_match('/[双单][数号]/u', $str) && !preg_match('/\d/u', $str))
            //    return array($str, '5');

            if(preg_match('/([双单][数号]|\d+)([\-以、][上后下]|$)/u', $str)){
                #if(preg_match('/\(.*?[含除包]/u', $str)) return array($str, false);
                #echo $str."<br>";
                if(preg_match('/分部/u', $str)) return array($str, false);
                if(preg_match('/[双单]号[^\d以、]/u', $str))
                    return array($str, false);
                if(preg_match('/\d{5,}/', $str)) return array($str, false);
                #echo $str."<br>";
                $tmp = preg_replace('/、?([双单][数号]\d?|\d+)(.*?$|$)/u','($1$2)', $str);
                if(mb_strlen(preg_match('/\(/u', $tmp))<2 && !preg_match('/[含除包]\d/u', $str) )return array($tmp, '_1');
                else return array($str, false);
                    
                #return array(preg_replace('/、?([双单][数号]\d?|\d+)([\-以、].*?$|$)/u','($1$2)', $str), '_1');
            }
            
        }
        return array($str, false);
    }
}
