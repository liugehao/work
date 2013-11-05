<?php

   function ordutf8($string, &$offset=0) {
    $code = ord(substr($string, $offset,1)); 
    if ($code >= 128) {        //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;                //110xxxxx
        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
        else if ($code < 248) $bytesnumber = 4;    //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset ++;
            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
            $codetemp = $codetemp*64 + $code2;
        }
        $code = $codetemp;
    }
    $offset += 1;
    if ($offset >= strlen($string)) $offset = -1;
    return $code;
}

   function rc4( $key_str, $data_str ) {
      // convert input string(s) to array(s)
      $key = array();
      $data = array();

      
      for ( $i = 0; $i < mb_strlen($key_str); $i++ ) {
         $key[] = ord($key_str{$i});
      }
      
      for ( $i = 0; $i < mb_strlen($data_str); $i++ ) {

         //$data[] = ordutf8(mb_substr($data_str, $i, 1, "UTF-8"));
         $data[] = ordutf8(mb_substr($data_str, $i, 1, "UTF-8"));
      }



     // prepare key
      $state = array( 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,
                      16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,
                      32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,
                      48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,
                      64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,
                      80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,
                      96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,
                      112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,
                      128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,
                      144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,
                      160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,
                      176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,
                      192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,
                      208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,
                      224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,
                      240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255 );
      $len = count($key);
      $index1 = $index2 = 0;
      for( $counter = 0; $counter < 256; $counter++ ){
         $index2   = ( $key[$index1] + $state[$counter] + $index2 ) % 256;
         $tmp = $state[$counter];
         $state[$counter] = $state[$index2];
         $state[$index2] = $tmp;
         $index1 = ($index1 + 1) % $len;
      }
      // rc4
      $len = count($data);

      $x = $y = 0;
      for ($counter = 0; $counter < $len; $counter++) {
         $x = ($x + 1) % 256;
         $y = ($state[$x] + $y) % 256;
         $tmp = $state[$x];
         $state[$x] = $state[$y];
         $state[$y] = $tmp;
         $data[$counter] ^= $state[($state[$x] + $state[$y]) % 256];
      }
      // convert output back to a string
      $data_str = "";
      for ( $i = 0; $i < $len; $i++ ) {
         $data_str .= unichr($data[$i]);
      }
      return $data_str;
   }
function unichr($intval) {return mb_convert_encoding(pack('n', $intval), 'UTF-8', 'UTF-16BE');}
function uniord($chr){ return unpack('n', mb_convert_encoding('好', 'UTF-16BE', 'UTF-8'));}


//var_dump(mb_convert_encoding(pack('n', 20320), 'UTF-8', 'UTF-16BE'));
//var_dump(unpack('n', mb_convert_encoding('好', 'UTF-16BE', 'UTF-8')));



?>
