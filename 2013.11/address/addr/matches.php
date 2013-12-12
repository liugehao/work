<?
class matches
{
    function replace1($str){
        if($str == '') {return '';}
        $tmp = preg_replace(array('/&gt;/u', '/&lt;/u'), array( '>','<'), $str);
        $tmp = preg_replace(array('/(\s*)?([\(（\[])/u', '/[\]\)））]/u', '/【更新时间.*?】/u'), array("(",")", ""), $tmp);
        $tmp = preg_replace('/[\s\r\n·\/、,，.;:。；：_|#◇◆《》★　●【】]|<.*?>|&\w*?;/u', ",", $tmp);
        $tmp = preg_replace('/[\-－—]+/u', '-', $tmp);
        $tmp = preg_replace('/\,+[\-－～]\,+/u', '-', $tmp);
        $tmp = preg_replace('/\,{2,}/', ",", $tmp);
        #一、二、三
        $tmp = preg_replace('/([一二三四五六七八九十]),/u', '$1.', $tmp);
        
        #$tmp = preg_replace_callback('/,(.*?)([一二三四五六七八九十],)+?(.+?),/u',);
        $tmp = preg_replace('/,((单号|双号)?\d+[\-号]?)/', "$1", $tmp);
        
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
        $tmp = preg_replace('/除不[派收]送范围.{0,3}?(其它?)全[部]?[派收][送]?/u', '全境派送', $tmp);
        $tmp = preg_replace('/除[派收]送范围.{0,3}?(其它?)全[部]?不[派收][送]?/u', '全境不派送', $tmp);
        
        $tmp = preg_replace_callback('/[\[\(].*?[\)\]]/u', 
                create_function(
                    '$matches',
                    'return preg_replace(\'_[/、,，.;:。；：|#,]_u\',"___", $matches[0] );'
                ),
                $tmp
            );
        
        return $tmp;
    }
    
    function matchstr($str){
        $str = preg_replace('/全境$/u', '', $str);
        
        if(preg_match('/区[域间]/u', $str))    return array($str, false);
        if(preg_match('/[东南西北][至到]/u', $str))    return array($str, false);
        if(preg_match('/以[东南西北]/u', $str))    return array($str, false);
        if(preg_match('/除不派送范围.{0,3}?其它全[部]?派[送]?/u', $str))
            return array($str, '9_1');
        if($str == '详情请点击') return array($str, false);
        if(preg_match('/^全境(不?)派送$/u', $str)) return array($str, '9');
        if(preg_match('/城区$/u', $str)) return array($str, '9_1');
        if(preg_match('/[城镇县市][区城](内|$)/u', $str)) return array($str, false);
        if(preg_match('/(\d+)号?-(\d+)/u', $str, $m)){
            if($m[1] > $m[2]) return array($str, false);
        }
        if(mb_strlen($str)<6 && mb_strlen($str)>1)    return array($str, '1');
        
        if(preg_match('/[^至到](工业园|博物馆|局|[一二三四五]期|[一二三四五六七八九十]中|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|大[楼厦院]|学校|分校|宿舍|屯|庄|村|庄|镇|乡|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|产业园区|集团|工业区|工业园区|开发区|体育馆|医院|汽车城|公寓|派出所|办公楼|综合楼|住宅楼|高中|法院|武装部|大队|软件园|园区|检察院|[一二三四五六七八九十百千万零]+区|写字楼|家属楼|商场|宿舍楼|加油站|宾馆|[旅酒饭][店馆]|部队|校区|城|厂|街|胡同|弄|路|道|巷|里|矿)$/u', $str) && !preg_match('/[()]/u', $str)){        
            return array($str, '2');
        }

        if(preg_match('/\(.*?\)$/u', $str)){
            if(preg_match('/\(.*?[含除]/u', $str)) return array($str, false);
            if(preg_match('/\(.*?(街|胡同|弄|路|道|巷|里)/u', $str)) return array($str, false);
            if(preg_match('/[^\d]\d{1,5}(号|栋)?(以上|以后|以下|([-到至]\d{1,5}(号|栋)?))?\)$/u', $str))
                return array($str, '3');
            //if(preg_match('/[^\d]\d{1,5}(号)?(以上|以后|以下|([-到至]\d{1,5}号?))?([双单][数号])?\([含除](此号)?\)$/u', $str))
            //    return '5';
        
        }else{
            //if(preg_match('/[()]/u', $str)) return false;
            if(preg_match('/[^\d]\d{1,5}(号|栋)?(以上|以后|以下|([-到至]\d{1,5}(号|栋)?))?([双单][数号])?$/u', $str))
                return array($str, '4');
            if(preg_match('/[双单][数号]/u', $str) && !preg_match('/\d/u', $str))
                return array($str, '5');
            
        }
        
        return array($str, false);
    }
}
