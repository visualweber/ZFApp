<?php

class App_Util_Util {

    public static function alias($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "a", $str);
        $str = preg_replace("/(B)/", "b", $str);
        $str = preg_replace("/(C)/", "c", $str);
        $str = preg_replace("/(đ|D|Đ)/", "d", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "e", $str);
        $str = preg_replace("/(F)/", "f", $str);
        $str = preg_replace("/(G)/", "g", $str);
        $str = preg_replace("/(H)/", "h", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ)/", "i", $str);
        $str = preg_replace("/(J)/", "j", $str);
        $str = preg_replace("/(K)/", "k", $str);
        $str = preg_replace("/(L)/", "l", $str);
        $str = preg_replace("/(M)/", "m", $str);
        $str = preg_replace("/(N)/", "n", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "o", $str);
        $str = preg_replace("/(P)/", "p", $str);
        $str = preg_replace("/(Q)/", "q", $str);
        $str = preg_replace("/(R)/", "r", $str);
        $str = preg_replace("/(S)/", "s", $str);
        $str = preg_replace("/(T)/", "t", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "u", $str);
        $str = preg_replace("/(V)/", "v", $str);
        $str = preg_replace("/(W)/", "w", $str);
        $str = preg_replace("/(X)/", "x", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "y", $str);
        $str = preg_replace("/(Z)/", "z", $str);
        $str = preg_replace("/(!|@|%|\^|\*|\(|\)|\+|\=|<|>|\?|\/|,|\.|\:|\;|\'|\"|\“|\”|\&|\#|\[|\]|~|$|_)/", "", $str);
        $str = str_replace("&*#39;", "", $str);
        return $str;
    }

    public static function wordLimit($str, $limit = 100, $strip_tags = true, $end_char = ' &#8230;') {
        if (trim($str) == '') {
            return $str;
        }

        if ($strip_tags) {
            $str = trim(preg_replace('#<[^>]+>#', ' ', $str));
        }
        $words = explode(' ', $str);
        $words = array_filter($words);
        $string = '';
        if (count($words) > $limit) {
            $i = 0;
            foreach ($words as $word) {
                if ($i < $limit) {
                    $string.=$word . ' ';
                    $i++;
                } else {
                    break;
                }
            }
        } else {
            $string = $str;
        }

        $string = self::removeSpace($string);

        return rtrim($string) . $end_char;
    }

    public static function characterLimit($str, $limit = 150, $strip_tags = true, $end_char = ' &#8230;', $enc = 'utf-8') {
        if (trim($str) == '') {
            return $str;
        }

        // Remove readmore tag from JCE
        $str = self::getReadmoreText($str);

        // always strip tags for text
        $str = self::remmoveImgTag($str);

        // Remove tag <p></p>
        $str = self::removeSpace($str);

        if ($strip_tags) {
            $str = strip_tags($str);
        }

        if (strlen($str) > $limit) {
            if (function_exists("mb_substr")) {
                $str = mb_substr($str, 0, $limit, $enc);
            } else {
                $str = substr($str, 0, $limit);
            }
            return rtrim($str) . $end_char;
        } else {
            return $str;
        }
    }

    public static function random($length = 8, $possible = "0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSXTUVYW") {
        // start with a blank string
        $string = "";

        // set up a counter
        $i = 0;

        // add random characters to $string until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

            // we don't want this character if it's already in the string
            if (!strstr($string, $char)) {
                $string .= $char;
                $i++;
            }
        }

        // done!
        return $string;
    }

}
