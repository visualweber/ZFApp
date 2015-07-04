<?php

require 'Thumbnail/imageLib.php';

class App_Util_Util {
    /*
     * Convert to safe characters
     * 
     * @desc : remove the accent not '-'
     */

    public static function vt_safe_vietnamese_meta($str, $lower = true, $vietnamese = true, $special = false, $accent = false) {
        $str = $lower ? strtolower($str) : $str;
        // Remove Vietnamese accent or not
        $str = $accent ? self::vt_remove_vietnamese_accent($str) : $str;

        // Replace special symbols with spaces or not
        $str = $special ? self::vt_remove_special_characters($str) : $str;

        // Replace Vietnamese characters or not
        $str = $vietnamese ? self::vt_replace_vietnamese_characters($str) : $str;

        return $str;
    }

    /*
     * Remove 5 Vietnamese accent / tone marks if has Combining Unicode characters
     * Tone marks: Grave (`), Acute(�), Tilde (~), Hook Above (?), Dot Bellow(.)
     */

    public static function vt_remove_vietnamese_accent($str) {

        $str = preg_replace("/[\x{0300}\x{0301}\x{0303}\x{0309}\x{0323}]/u", "", $str);

        return $str;
    }

    /*
     * Remove or Replace special symbols with spaces
     */

    public static function vt_remove_special_characters($str, $remove = true) {

        // Remove or replace with spaces
        $substitute = $remove ? "" : " ";

        $str = preg_replace("/[\x{0021}-\x{002D}\x{002F}\x{003A}-\x{0040}\x{005B}-\x{0060}\x{007B}-\x{007E}\x{00A1}-\x{00BF}]/u", $substitute, $str);

        return $str;
    }

    /*
     * Replace Vietnamese vowels with diacritic and Letter D with Stroke with corresponding English characters
     */

    public static function vt_replace_vietnamese_characters($str) {

        $str = preg_replace("/[\x{00C0}-\x{00C3}\x{00E0}-\x{00E3}\x{0102}\x{0103}\x{1EA0}-\x{1EB7}]/u", "a", $str);
        $str = preg_replace("/[\x{00C8}-\x{00CA}\x{00E8}-\x{00EA}\x{1EB8}-\x{1EC7}]/u", "e", $str);
        $str = preg_replace("/[\x{00CC}\x{00CD}\x{00EC}\x{00ED}\x{0128}\x{0129}\x{1EC8}-\x{1ECB}]/u", "i", $str);
        $str = preg_replace("/[\x{00D2}-\x{00D5}\x{00F2}-\x{00F5}\x{01A0}\x{01A1}\x{1ECC}-\x{1EE3}]/u", "o", $str);
        $str = preg_replace("/[\x{00D9}-\x{00DA}\x{00F9}-\x{00FA}\x{0168}\x{0169}\x{01AF}\x{01B0}\x{1EE4}-\x{1EF1}]/u", "u", $str);
        $str = preg_replace("/[\x{00DD}\x{00FD}\x{1EF2}-\x{1EF9}]/u", "y", $str);
        $str = preg_replace("/[\x{0110}\x{0111}]/u", "d", $str);

        return $str;
    }

    public static function alias($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|A|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "a", $str);
        $str = preg_replace("/(B)/", "b", $str);
        $str = preg_replace("/(C)/", "c", $str);
        $str = preg_replace("/(đ|D|Đ)/", "d", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|E|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "e", $str);
        $str = preg_replace("/(F)/", "f", $str);
        $str = preg_replace("/(G)/", "g", $str);
        $str = preg_replace("/(H)/", "h", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ)/", "i", $str);
        $str = preg_replace("/(J)/", "j", $str);
        $str = preg_replace("/(K)/", "k", $str);
        $str = preg_replace("/(L)/", "l", $str);
        $str = preg_replace("/(M)/", "m", $str);
        $str = preg_replace("/(N)/", "n", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|O|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "o", $str);
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
        $str = str_replace(" ", "-", $str);
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

    public static function Timer($timestamp) {
        $etime = time() - $timestamp;
        if ($etime < 1) {
            return 'bây giờ';
        }
        $a = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            7 * 24 * 60 * 60 => 'week',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $a_plural = array('year' => 'năm',
            'month' => 'tháng',
            'week' => 'tuần',
            'day' => 'ngày',
            'hour' => 'giờ',
            'minute' => 'phút',
            'second' => 'giây'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $a_plural[$str] . ' ' . 'trước';
            }
        }
    }

    public static function resizeIMG($path, $thumb_path, $width, $height, $type = "crop") {
        $src = new imageLib($path);
        $src->resizeImage($width, $height, $type, true);
        $src->saveImage($thumb_path);
        return true;
    }

    public function getDateInWeek($week, $year) {
        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7 * $week) + 1 - $day) * 24 * 3600;
        $return[0] = date('d-m-Y', $time);
        $time += 6 * 24 * 3600;
        $return[1] = date('d-m-Y', $time);
        return $return;
    }

}
