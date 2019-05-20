<?php
/**
 * encryptCanon
 * @author: canon
 * @website: https://github.com/canon1014
 * @date: 2019-05-19
 */
namespace encryptCanon;
class encryptCanon
{
    protected $string;
    protected $key;
    protected $stringBin;
    protected $keyBin;
    public $secretString;

    public function encrypt($string, $key)
    {
        $this->secretString = '';
        $stringBin  = $this->strToBin($string);
        $keyBin = $this->strToBin($key);
        $stringBinArray = explode(',',$stringBin);
        $keyBinArray = explode(',',$keyBin);

        foreach($stringBinArray as $stringBinKey=>$stringBinValue){
            if (isset($keyBinArray[$stringBinKey])){
                $this->secretString.= $this->stringXor($stringBinArray[$stringBinKey],$keyBinArray[$stringBinKey]);
            }else{
                $this->secretString .= $this->stringXor($stringBinArray[$stringBinKey],$keyBinArray[$stringBinKey%count($keyBinArray)]);
            }
        }
      
        return $this->toAscii($this->secretString);
        
    }

    public function stringXor($str1,$str2){
        $result = '';
        for ($i=0; $i < strlen($str1); $i++) {
            $string1 = substr($str1,$i,1);
            $string2 = substr($str2,$i,1);
            if($string1 == $string2){
                $result .= 0;
            }else{
                $result .= 1;
            }
        }
        return $result;
    }

    public function strToBin($str){
        $arr = preg_split('/(?<!^)(?!$)/u', $str);
        foreach($arr as &$v){
            $temp = unpack('H*', $v);
            $v = base_convert($temp[1], 16, 2);
            unset($temp);
            $length = 8-strlen($v);
            if ($length>=0) {
                for ($i=0; $i < $length; $i++) { 
                    $v = '0'.$v;
                }
                
            }
        }
        return join(',',$arr);
    }

    public function BinToStr($str){
        $arr = explode(',', $str);
        foreach($arr as &$v){
            $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
        }
        return join('', $arr);
    }

    public function toAscii($string)
    {
        $asciiArray = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9','+','/'];
        $a = strlen($string)%24;
        $result = "";
        if ($a != 0) { 
            for ($i=0; $i < 24-$a; $i++) { 
                $string = $string.'0';
            }
        }
        $array = str_split($string,6);
        for ($i=0; $i < count($array)-((24-$a)/8); $i++) { 
            $result .= $asciiArray[base_convert($array[$i], 2, 10)];
        }
        if($a != 0){
            for ($i=0; $i < (24-$a)/8; $i++) {
                $result .= '=';
            }
        }

        return $result;
    }
}
