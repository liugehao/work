<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");



header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");


header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

require_once( "rc4.php" );
$_POST['s'] = isset($_POST['s'])?$_POST['s']:'你好!美女!';
$_POST['key'] = isset($_POST['key'])?$_POST['key']:'111111111111111111111';
   
?>
<html>
  <head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>RC4 Encryption</title>
  </head>
  <body>
<script language="JavaScript"  charset="UTF-8"><!--
 
    function rc4( key_str, data_str ) {
        var key = new Array(),data = new Array();
	    for( var i=0;i< key_str.length;i++){
	        key.push( key_str[i].charCodeAt(0) );
	       
	    }

        for ( i = 0; i < data_str.length; i++ ) {
            data.push( data_str[i].charCodeAt(0)) ; 
            //alert(data_str[i] + " "+data_str[i].charCodeAt(0) );
        }

       var state = new Array( 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,
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
                      
      var len = key.length;
      
      var index1 = 0, index2 = 0;
      for(var counter = 0; counter < 256; counter++ ){ 
         index2   = ( key[index1] + state[counter] + index2 ) % 256;
         tmp = state[counter];
         state[counter] = state[index2];
         state[index2] = tmp;
         index1 = (index1 + 1) % len;
      }
      // rc4
      len = data.length;
      //alert(len);
      var x = 0, y = 0;
      for (counter = 0; counter < len; counter++) {
         x = (x + 1) % 256;
         y = (state[x] + y) % 256;
         tmp = state[x];
         state[x] = state[y];
         state[y] = tmp;
         data[counter] ^= state[(state[x] + state[y]) % 256];
      }
      // convert output back to a string
      var data_str = "";
      for ( i = 0; i < len; i++ ) {
         data_str += String.fromCharCode(data[i]);
      }

      return data_str;
   }


function utf16to8(str) {
    var out, i, len, c;

    out = "";
    len = str.length;
    for(i = 0; i < len; i++) {
        c = str.charCodeAt(i);
        if ((c >= 0x0001) && (c <= 0x007F)) {
            out += str.charAt(i);
        } else if (c > 0x07FF) {
            out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
            out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        } else {
            out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));
            out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));
        }
    }
    return out;
}

function utf8to16(str) {
    var out, i, len, c;
    var char2, char3;

    out = "";
    len = str.length;
    i = 0;
    while(i < len) {
        c = str.charCodeAt(i++);
        switch(c >> 4)
        { 
          case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
            // 0xxxxxxx
            out += str.charAt(i-1);
            break;
          case 12: case 13:
            // 110x xxxx   10xx xxxx
            char2 = str.charCodeAt(i++);
            out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
            break;
          case 14:
            // 1110 xxxx  10xx xxxx  10xx xxxx
            char2 = str.charCodeAt(i++);
            char3 = str.charCodeAt(i++);
            out += String.fromCharCode(((c & 0x0F) << 12) |
                                           ((char2 & 0x3F) << 6) |
                                           ((char3 & 0x3F) << 0));
            break;
        }
    }

    return out;
}

/* Copyright (C) 1999 Masanao Izumo <iz@onicos.co.jp>
 * Version: 1.0
 * LastModified: Dec 25 1999
 * This library is free.  You can redistribute it and/or modify it.
 */

/*
 * Interfaces:
 * b64 = base64encode(data);
 * data = base64decode(b64);
 */


var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64DecodeChars = new Array(
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
    -1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function base64encode(str) {
    var out, i, len;
    var c1, c2, c3;

    len = str.length;
    i = 0;
    out = "";
    while(i < len) {
        c1 = str.charCodeAt(i++) & 0xff;
        if(i == len)
        {
            out += base64EncodeChars.charAt(c1 >> 2);
            out += base64EncodeChars.charAt((c1 & 0x3) << 4);
            out += "==";
            break;
        }
        c2 = str.charCodeAt(i++);
        if(i == len)
        {
            out += base64EncodeChars.charAt(c1 >> 2);
            out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
            out += base64EncodeChars.charAt((c2 & 0xF) << 2);
            out += "=";
            break;
        }
        c3 = str.charCodeAt(i++);
        out += base64EncodeChars.charAt(c1 >> 2);
        out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
        out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
        out += base64EncodeChars.charAt(c3 & 0x3F);
    }
    return out;
}

function base64decode(str) {
    var c1, c2, c3, c4;
    var i, len, out;

    len = str.length;
    i = 0;
    out = "";
    while(i < len) {
        /* c1 */
        do {
            c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        } while(i < len && c1 == -1);
        if(c1 == -1)
            break;

        /* c2 */
        do {
            c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        } while(i < len && c2 == -1);
        if(c2 == -1)
            break;

        out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

        /* c3 */
        do {
            c3 = str.charCodeAt(i++) & 0xff;
            if(c3 == 61)
                return out;
            c3 = base64DecodeChars[c3];
        } while(i < len && c3 == -1);
        if(c3 == -1)
            break;

        out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));

        /* c4 */
        do {
            c4 = str.charCodeAt(i++) & 0xff;
            if(c4 == 61)
                return out;
            c4 = base64DecodeChars[c4];
        } while(i < len && c4 == -1);
        if(c4 == -1)
            break;
        out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
    }
    return out;
}
//input base64 encode

function strdecode(str){
        return utf8to16(base64decode(str));
}
function strencode(str){
        return base64encode(utf16to8(str));
}

</script>
              <script language="JavaScript" charset="UTF-8">
                <!--



var key = "<?php echo $_POST['key'];?>",   s= "<?php echo $_POST['s'];?>";


document.write("javascript:<br>");
document.write("source:"+s +"<br>");
var rc4s = rc4(key,s);
document.write("rc4: "+ rc4s +"<br>");
var b64s = strencode(rc4s);
document.write("base64: "+ b64s +"<br>");
var b64des = strdecode( b64s);
document.write("base64decode: "+ b64des +"<br>");
document.write("source:"+ rc4(key,b64des) +"<br>");
document.write("<hr>");

--></script>
<?php
    echo "php:<br>";
   $key = $_POST['key'];//"111111111111111111111";
   $s = $_POST['s'];//"你好!美女!sdfsdfds";
   echo "source: $s <br>";
   $ciphertext = rc4( $key, $s );
   echo "rc4: $ciphertext <br>";
   $b64 =base64_encode($ciphertext);
    echo "base64: $b64 <br>";
    $b64d = base64_decode($b64 );
  echo "base64decode: $b64d <br>";
   $decrypted = rc4( $key,$b64d );
  echo "desource : $decrypted <br>";
?>
    <h3>RC4 Encryption (input text for both field):</h3>
    <form method="POST" name="rc4">
      <div align="center">
        <table border="0">
          <tr>
            <td align="right">
              Key:
            </td>
            <td>
              <input type="text" size="60" name="key" value="<?php echo $_POST['key'];?>">
            </td>
          </tr>
          <tr>
            <td align="right">
              Text:
            </td>
            <td>
              <textarea name="s" rows="6" cols="70"><?php echo $_POST['s'];?></textarea>
            </td>
          </tr>
          <tr>
            <td>
              <p align="center">
                <input type="submit" value="submit">

              </p>
            </td>
          </tr>
        </table>
      </div>
    </form>
  </body>
</html>



